import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'
import { flushPromises, mount } from '@vue/test-utils'
import { nextTick } from 'vue'
import { createPinia, setActivePinia } from 'pinia'
import api from '@/store/axiosManager'
import fx from '@/store/useFunctions'
import { useUserDataStore } from '@/store/userDataStore'
import ComponentLive from '@/views/User/ComponentLive.vue'
import MatchFormModal from '@/components/modals/userModals/MatchFormModal.vue'
import predictionModal from '@/components/modals/predictionModal.vue'

const teamData = { live_id: 9, match_stage: 'Final', home_team: 'Home', away_team: 'Away', home_team_score: 0, away_team_score: 0, curr_time: 10, isPaused: false }
const mountLive = () => mount(ComponentLive, { props: { teamData } })
const goalButton = 'button[aria-label="Goal for Home"]'
const modalStub = { BaseModal: { template: '<div><slot /></div>' } }

describe('live scoring keeps fans up to date', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.useFakeTimers()
    })
    afterEach(() => {
        vi.useRealTimers()
        vi.restoreAllMocks()
    })

    it('still sends a goal when the scorer leaves the page straight after tapping', async () => {
        const update = vi.spyOn(api, 'updateLiveMatch').mockResolvedValue({ data: {} } as any)
        const wrapper = mountLive()

        await wrapper.find(goalButton).trigger('click')
        await nextTick()
        wrapper.unmount()

        expect(update).toHaveBeenCalledTimes(1)
        expect(update.mock.calls[0]![1]).toMatchObject({ home_team_score: 1 })
    })

    it('does not send anything on leaving when nothing changed', async () => {
        const update = vi.spyOn(api, 'updateLiveMatch').mockResolvedValue({ data: {} } as any)
        mountLive().unmount()
        expect(update).not.toHaveBeenCalled()
    })

    it('retries a failed update on its own, even while the clock is paused', async () => {
        const update = vi.spyOn(api, 'updateLiveMatch')
            .mockRejectedValueOnce(new Error('offline'))
            .mockResolvedValue({ data: {} } as any)
        const wrapper = mountLive()

        await wrapper.find('.clock-controls button:nth-child(2)').trigger('click') // pause
        await vi.advanceTimersByTimeAsync(800)
        expect(update).toHaveBeenCalledTimes(1)
        expect(wrapper.text()).toContain('Not saved')

        await vi.advanceTimersByTimeAsync(5000)
        expect(update).toHaveBeenCalledTimes(2)
        expect(wrapper.text()).toContain('Fans are up to date')
        wrapper.unmount()
    })

    it('never lets a slow older update overwrite a newer score', async () => {
        let finishFirst = (_: unknown) => {}
        const update = vi.spyOn(api, 'updateLiveMatch')
            .mockReturnValueOnce(new Promise((resolve) => { finishFirst = resolve }) as any)
            .mockResolvedValue({ data: {} } as any)
        const wrapper = mountLive()

        await wrapper.find(goalButton).trigger('click')
        await vi.advanceTimersByTimeAsync(800)
        await wrapper.find(goalButton).trigger('click')
        await vi.advanceTimersByTimeAsync(800)
        expect(update).toHaveBeenCalledTimes(1) // second waits for the first

        finishFirst({ data: {} })
        await vi.advanceTimersByTimeAsync(0)
        expect(update).toHaveBeenCalledTimes(2)
        expect(update.mock.calls[1]![1]).toMatchObject({ home_team_score: 2 })
        wrapper.unmount()
    })

})

describe('admin data', () => {
    beforeEach(() => setActivePinia(createPinia()))
    afterEach(() => vi.restoreAllMocks())

    it('ignores a slow reply for a tournament the admin has switched away from', async () => {
        const store = useUserDataStore()
        let replyForOld = (_: unknown) => {}
        vi.spyOn(api, 'getTournamentTeams')
            .mockReturnValueOnce(new Promise((resolve) => { replyForOld = resolve }) as any)
            .mockResolvedValueOnce({ data: [{ team_id: 'new' }] } as any)

        const slow = store.getTournamentTeams('old')
        await store.getTournamentTeams('new')
        replyForOld({ data: [{ team_id: 'old' }] })
        await slow

        expect(store.tournamentTeams).toEqual([{ team_id: 'new' }])
    })

    it('does not let a request that succeeds hide one that failed', async () => {
        const store = useUserDataStore()
        vi.spyOn(api, 'getTournamentMatches').mockRejectedValue(new Error('offline'))
        vi.spyOn(api, 'getTournamentTeams').mockResolvedValue({ data: [] } as any)

        await store.getTournamentMatches('t')
        await store.getTournamentTeams('t')

        expect(store.apiError).toBe(true)
    })

    it('forgets the previous account on sign-out', () => {
        const store = useUserDataStore()
        store.tournaments = [{ tour_id: 't1' }]
        store.feedback = [{ feedback_id: 1 }]
        store.subUsers = [{ subuser_id: 1 }]
        store.dashboardFigures = { teams: 4 }

        store.reset()

        expect(store.tournaments).toEqual([])
        expect(store.feedback).toEqual([])
        expect(store.subUsers).toEqual([])
        expect(store.dashboardFigures).toBeNull()
    })
})

describe('forms with teams that depend on each other', () => {
    beforeEach(() => setActivePinia(createPinia()))
    afterEach(() => vi.restoreAllMocks())

    const teams = [{ team_id: 'a', team_name: 'A' }, { team_id: 'b', team_name: 'B' }, { team_id: 'c', team_name: 'C' }, { team_id: 'd', team_name: 'D' }]

    it('clears the away team when the same team is then picked at home', async () => {
        useUserDataStore().tournamentTeams = teams
        const wrapper = mount(MatchFormModal, { props: { tournament: { tour_id: 't', type: 'league' } }, global: { stubs: modalStub } })
        const [home, away] = wrapper.findAll('select')

        await home!.setValue('a')
        await away!.setValue('b')
        await home!.setValue('b')
        await flushPromises()

        expect((away!.element as HTMLSelectElement).value).toBe('')
    })

    it('clears a lower prediction when that team is then picked higher up', async () => {
        const wrapper = mount(predictionModal, { props: { teams, tour_id: 't' }, global: { stubs: modalStub } })
        const [first, second, third] = wrapper.findAll('select')

        await first!.setValue('a')
        await second!.setValue('b')
        await third!.setValue('c')
        await first!.setValue('c')
        await flushPromises()

        expect((third!.element as HTMLSelectElement).value).toBe('')
        expect((second!.element as HTMLSelectElement).value).toBe('b')
    })
})
