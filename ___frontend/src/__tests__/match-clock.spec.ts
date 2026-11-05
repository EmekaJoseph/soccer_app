import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'
import { mount } from '@vue/test-utils'
import { createPinia, setActivePinia } from 'pinia'
import api from '@/store/axiosManager'
import { useStatsStore } from '@/store/statsStore'
import ComponentLive from '@/views/User/ComponentLive.vue'
import LivePanel from '@/views/StatsView/live.vue'
import { liveMinute } from '@/views/StatsView/liveClock'

const START = new Date('2026-11-05T15:00:00Z').getTime()
const teamData = { live_id: 9, match_stage: 'Final', home_team: 'Home', away_team: 'Away', home_team_score: 0, away_team_score: 0, curr_time: 10, isPaused: false }

beforeEach(() => {
    setActivePinia(createPinia())
    vi.useFakeTimers()
    vi.setSystemTime(START)
})
afterEach(() => {
    vi.useRealTimers()
    vi.restoreAllMocks()
})

describe('what fans see', () => {
    it('counts on from the last minute received', () => {
        const live = { curr_time: 10, isPaused: false, syncedAt: START }
        expect(liveMinute(live, START + 59_000)).toBe(10)
        expect(liveMinute(live, START + 60_000)).toBe(11)
        expect(liveMinute(live, START + 20 * 60_000)).toBe(30)
    })

    it('stays still while paused, and stops at the maximum', () => {
        expect(liveMinute({ curr_time: 45, isPaused: true, syncedAt: START }, START + 600_000)).toBe(45)
        expect(liveMinute({ curr_time: 199, isPaused: false, syncedAt: START }, START + 3_600_000)).toBe(200)
    })

    it('copes with a phone clock set behind the moment of the update', () => {
        expect(liveMinute({ curr_time: 10, isPaused: false, syncedAt: START }, START - 600_000)).toBe(10)
    })

    it('ticks on the page with no new data arriving', async () => {
        vi.spyOn(api, 'getLiveMatches').mockResolvedValue({
            data: [{ live_id: 1, curr_time: 67, isPaused: false, home_team_score: 2, away_team_score: 1, home_team: { team_name: 'H' }, away_team: { team_name: 'A' } }],
        } as any)
        const stats = useStatsStore()
        stats.tour_id = 't'
        await stats.getLiveMatches()

        const wrapper = mount(LivePanel, { global: { stubs: { emptyDataComponent: true, TeamBadge: true } } })
        expect(wrapper.text()).toContain("Live 67'")

        await vi.advanceTimersByTimeAsync(120_000)
        expect(wrapper.text()).toContain("Live 69'")
    })

    it('restarts its count from each pushed update', async () => {
        vi.spyOn(api, 'getLiveMatches').mockResolvedValue({ data: [{ live_id: 1, curr_time: 67, isPaused: false }] } as any)
        const stats = useStatsStore()
        stats.tour_id = 't'
        await stats.getLiveMatches()

        vi.setSystemTime(START + 5 * 60_000)
        stats.applyLiveUpdate({ tour_id: 't', live_id: 1, results: { home_team_score: 0, away_team_score: 0, curr_time: 72, isPaused: false } })

        expect(liveMinute(stats.tourLives[0], Date.now())).toBe(72)
    })
})

describe('what the scorer sees', () => {
    const mountBoard = (extra = {}) => mount(ComponentLive, { props: { teamData: { ...teamData, ...extra } } })
    const clock = (wrapper: ReturnType<typeof mountBoard>) => wrapper.find('.clock').text()

    beforeEach(() => {
        vi.spyOn(api, 'updateLiveMatch').mockResolvedValue({ data: {} } as any)
    })

    it('catches up straight away after the phone was locked', async () => {
        const wrapper = mountBoard()

        // A locked phone freezes timers, so time passes without any of them firing.
        vi.setSystemTime(START + 20 * 60_000)
        document.dispatchEvent(new Event('visibilitychange'))
        await wrapper.vm.$nextTick()

        expect(clock(wrapper)).toBe("30'")
        wrapper.unmount()
    })

    it('keeps correct time from then on, without jumping back', async () => {
        const update = vi.mocked(api.updateLiveMatch)
        const wrapper = mountBoard()

        vi.setSystemTime(START + 20 * 60_000)
        await vi.advanceTimersByTimeAsync(5_000)
        expect(clock(wrapper)).toBe("30'")

        await vi.advanceTimersByTimeAsync(60_000)
        expect(clock(wrapper)).toBe("31'")
        expect(update.mock.lastCall![1]).toMatchObject({ curr_time: 31 })
        wrapper.unmount()
    })

    it('counts on from a manual correction', async () => {
        const wrapper = mountBoard()

        await wrapper.find('button[aria-label="Plus one minute"]').trigger('click')
        expect(clock(wrapper)).toBe("11'")

        await vi.advanceTimersByTimeAsync(60_000)
        expect(clock(wrapper)).toBe("12'")
        wrapper.unmount()
    })

    it('stays still while paused and resumes from the same minute', async () => {
        const wrapper = mountBoard()
        const pause = wrapper.find('.clock-controls button:nth-child(2)')

        await pause.trigger('click')
        await vi.advanceTimersByTimeAsync(300_000)
        expect(clock(wrapper)).toBe("10'")

        await pause.trigger('click')
        expect(clock(wrapper)).toBe("10'")
        await vi.advanceTimersByTimeAsync(60_000)
        expect(clock(wrapper)).toBe("11'")
        wrapper.unmount()
    })

    it('starts a paused match without counting the pause', async () => {
        const wrapper = mountBoard({ isPaused: true })
        await vi.advanceTimersByTimeAsync(300_000)
        expect(clock(wrapper)).toBe("10'")
        wrapper.unmount()
    })
})
