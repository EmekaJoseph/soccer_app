import { beforeEach, describe, expect, it, vi } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import { useStatsStore } from '@/store/statsStore'
import api from '@/store/axiosManager'

const payload = (live_id: number, home: number, away: number) => ({
    tour_id: 't1', live_id, results: { home_team_score: home, away_team_score: away, curr_time: 30, isPaused: false },
})

describe('stats store live updates', () => {
    beforeEach(() => setActivePinia(createPinia()))

    it('applies scores and reports goals', () => {
        const stats = useStatsStore()
        stats.tourLives = [{ live_id: 7, home_team_score: 0, away_team_score: 0, curr_time: 10 }]

        expect(stats.applyLiveUpdate(payload(7, 1, 0))).toBe(true)
        expect(stats.tourLives[0]).toMatchObject({ home_team_score: 1, curr_time: 30 })

        // Same score again (e.g. the clock moved) is not a goal.
        expect(stats.applyLiveUpdate(payload(7, 1, 0))).toBe(false)
    })

    it('reloads instead of crashing for an unknown live match', () => {
        const stats = useStatsStore()
        const spy = vi.spyOn(api, 'getLiveMatches').mockResolvedValue({ data: [] } as any)

        expect(stats.applyLiveUpdate(payload(99, 1, 0))).toBe(false)
        expect(spy).toHaveBeenCalled()
    })

    it('removes ended matches', () => {
        const stats = useStatsStore()
        stats.tourLives = [{ live_id: 1 }, { live_id: 2 }]
        stats.removeLive(1)
        expect(stats.tourLives.map((l) => l.live_id)).toEqual([2])
    })

    it('clears the previous tournament when loading another one', async () => {
        const stats = useStatsStore()
        stats.tourResults = [{ result_id: 'old' }]
        vi.spyOn(api, 'tour_data').mockRejectedValue({ response: { status: 404 } })

        await stats.load('missing')
        expect(stats.tourResults).toEqual([])
        expect(stats.notFound).toBe(true)
        expect(stats.apiLoading).toBe(false)
    })
})
