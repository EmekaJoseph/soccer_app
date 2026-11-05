import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'
import { flushPromises, mount } from '@vue/test-utils'
import { createPinia, setActivePinia } from 'pinia'
import api from '@/store/axiosManager'
import fx from '@/store/useFunctions'
import EndLiveMatchModal from '@/components/modals/EndLiveMatchModal.vue'
import otherLiveMatchesModal from '@/components/modals/otherLiveMatchesModal.vue'
import ComponentLive from '@/views/User/ComponentLive.vue'

const global = { stubs: { BaseModal: { template: '<div><slot /></div>' }, componentLoadingSpinner: true, emptyDataComponent: true } }
const live = (extra = {}) => ({ live_id: 9, match_stage: 'Final', home_team: 'Home', away_team: 'Away', home_team_score: 1, away_team_score: 1, curr_time: 90, ...extra })

const saveButton = 'button[type=submit]'
const endOnlyButton = 'button[type=button]'

describe('end match dialog', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.spyOn(fx.toast, 'success').mockImplementation(() => ({}) as any)
    })
    afterEach(() => vi.restoreAllMocks())

    const mountDialog = (match = live()) => mount(EndLiveMatchModal, { props: { live: match }, global })
    const submit = async (wrapper: ReturnType<typeof mountDialog>) => {
        await wrapper.find('form').trigger('submit')
        await flushPromises()
    }

    it('asks for penalties when a knock-out match is level', () => {
        expect(mountDialog().text()).toContain('Decided on penalties?')
    })

    it.each([
        ['the score is not level', live({ home_team_score: 2 })],
        ['it is a group game', live({ match_stage: 'Group_Stage' })],
        ['it is a friendly', live({ match_stage: 'Friendly' })],
        ['it is a league game', live({ match_stage: null })],
    ])('does not ask for penalties when %s', (_, match) => {
        expect(mountDialog(match).text()).not.toContain('Decided on penalties?')
    })

    it('sends the penalties with the result', async () => {
        const end = vi.spyOn(api, 'endLiveMatch').mockResolvedValue({ data: {} } as any)
        const wrapper = mountDialog()
        const [home, away] = wrapper.findAll('input[type=number]')
        await home!.setValue(5)
        await away!.setValue(4)
        await submit(wrapper)

        expect(end).toHaveBeenCalledWith(9, true, { home_score_pen: 5, away_score_pen: 4 })
        expect(wrapper.emitted('ended')).toHaveLength(1)
    })

    it('lets a level knock-out be saved as a draw when penalties are unticked', async () => {
        const end = vi.spyOn(api, 'endLiveMatch').mockResolvedValue({ data: {} } as any)
        const wrapper = mountDialog()
        await wrapper.find('input[type=checkbox]').setValue(false)
        expect(wrapper.find('input[type=number]').exists()).toBe(false)
        await submit(wrapper)

        expect(end).toHaveBeenCalledWith(9, true, {})
    })

    it('sends no penalties for a match that cannot have them', async () => {
        const end = vi.spyOn(api, 'endLiveMatch').mockResolvedValue({ data: {} } as any)
        const wrapper = mountDialog(live({ home_team_score: 2 }))
        await submit(wrapper)

        expect(end).toHaveBeenCalledWith(9, true, {})
    })

    it('"End only" records no result and skips the penalty fields', async () => {
        const end = vi.spyOn(api, 'endLiveMatch').mockResolvedValue({ data: {} } as any)
        const wrapper = mountDialog()
        await wrapper.find(endOnlyButton).trigger('click')
        await flushPromises()

        expect(end).toHaveBeenCalledWith(9, false, {})
    })

    it('shows the server error, stays open and tells the scoreboard to carry on', async () => {
        vi.spyOn(api, 'endLiveMatch').mockRejectedValue({ isAxiosError: true, response: { data: { message: 'A penalty shoot-out must have a winner.' } } })
        const wrapper = mountDialog()
        await submit(wrapper)

        expect(wrapper.text()).toContain('A penalty shoot-out must have a winner.')
        expect(wrapper.emitted('ended')).toBeUndefined()
        expect(wrapper.emitted('failed')).toHaveLength(1)
    })
})

describe('scoreboard', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.useFakeTimers()
        vi.spyOn(fx.toast, 'success').mockImplementation(() => ({}) as any)
        vi.spyOn(fx.toast, 'info').mockImplementation(() => ({}) as any)
    })
    afterEach(() => {
        vi.useRealTimers()
        vi.restoreAllMocks()
    })

    const mountBoard = () => mount(ComponentLive, { props: { teamData: live({ home_team_score: 0, away_team_score: 0 }) }, global })

    it('saves the final score, then ends, then sends nothing more', async () => {
        const update = vi.spyOn(api, 'updateLiveMatch').mockResolvedValue({ data: {} } as any)
        const end = vi.spyOn(api, 'endLiveMatch').mockResolvedValue({ data: {} } as any)
        const wrapper = mountBoard()

        await wrapper.find('button[aria-label="Goal for Home"]').trigger('click')
        await wrapper.find('.ui-btn-danger').trigger('click')
        await wrapper.find('form').trigger('submit')
        await vi.advanceTimersByTimeAsync(0)

        expect(update.mock.lastCall![1]).toMatchObject({ home_team_score: 1 })
        expect(end).toHaveBeenCalledWith(9, true, {})
        expect(wrapper.emitted('ended')).toHaveLength(1)

        const sent = update.mock.calls.length
        await vi.advanceTimersByTimeAsync(70_000)
        wrapper.unmount()
        expect(update).toHaveBeenCalledTimes(sent)
    })

    it('carries on updating if ending fails', async () => {
        const update = vi.spyOn(api, 'updateLiveMatch').mockResolvedValue({ data: {} } as any)
        vi.spyOn(api, 'endLiveMatch').mockRejectedValue(new Error('offline'))
        const wrapper = mountBoard()

        await wrapper.find('.ui-btn-danger').trigger('click')
        await wrapper.find('form').trigger('submit')
        await vi.advanceTimersByTimeAsync(0)
        const sent = update.mock.calls.length

        await wrapper.find('button[aria-label="Goal for Home"]').trigger('click')
        await vi.advanceTimersByTimeAsync(800)
        expect(update.mock.calls.length).toBe(sent + 1)
        wrapper.unmount()
    })

    it('clears itself when the owner has already ended the match', async () => {
        vi.spyOn(api, 'updateLiveMatch').mockRejectedValue({ isAxiosError: true, response: { status: 404 } })
        const wrapper = mountBoard()

        await wrapper.find('button[aria-label="Goal for Home"]').trigger('click')
        await vi.advanceTimersByTimeAsync(800)

        expect(wrapper.emitted('ended')).toHaveLength(1)
        expect(fx.toast.info).toHaveBeenCalled()

        await vi.advanceTimersByTimeAsync(10_000)
        expect(api.updateLiveMatch).toHaveBeenCalledTimes(1) // no retrying a match that is gone
        wrapper.unmount()
    })
})

describe('owner: all live matches', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.spyOn(fx.toast, 'success').mockImplementation(() => ({}) as any)
    })
    afterEach(() => vi.restoreAllMocks())

    it('can end a match someone else is scoring, then refreshes the list', async () => {
        const list = vi.spyOn(api, 'getLiveMatchesForAdmin')
            .mockResolvedValueOnce({ data: [live({ creator: { email: 'scorer@example.com' }, isMe: null })] } as any)
            .mockResolvedValueOnce({ data: [] } as any)
        const end = vi.spyOn(api, 'endLiveMatch').mockResolvedValue({ data: {} } as any)
        const update = vi.spyOn(api, 'updateLiveMatch')

        const wrapper = mount(otherLiveMatchesModal, { props: { tour: { tour_id: 't' } }, global })
        await flushPromises()
        expect(wrapper.text()).toContain('scorer@example.com')

        await wrapper.find('.ui-btn-danger').trigger('click')
        await wrapper.find(endOnlyButton).trigger('click')
        await flushPromises()

        expect(end).toHaveBeenCalledWith(9, false, {})
        expect(update).not.toHaveBeenCalled() // the owner must not overwrite the scorer's score
        expect(list).toHaveBeenCalledTimes(2)
        expect(wrapper.emitted('changed')).toHaveLength(1)
    })
})
