import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import { useStatsStore } from '@/store/statsStore'
import api from '@/store/axiosManager'

const LISTS = ['standings', 'results', 'matches', 'getLiveMatches', 'infomationCenter', 'publicPlayers'] as const

function mockApi(results: object[] = [{ result_id: 1 }]) {
    vi.spyOn(api, 'tour_data').mockResolvedValue({ data: { tour_title: 'Cup', tour_type: 'cup', tour_logo: null, tour_desc: null } } as any)
    for (const name of LISTS) vi.spyOn(api, name).mockResolvedValue({ data: [] } as any)
    vi.spyOn(api, 'results').mockResolvedValue({ data: results } as any)
}

describe('stats page refresh', () => {
    beforeEach(() => setActivePinia(createPinia()))
    afterEach(() => vi.restoreAllMocks())

    it('keeps the page and its data when a background refresh fails', async () => {
        mockApi()
        const stats = useStatsStore()
        await stats.load('t1')

        vi.spyOn(api, 'results').mockRejectedValue(new Error('offline'))
        await stats.refresh()

        expect(stats.apiError).toBe(false)
        expect(stats.syncError).toBe(true)
        expect(stats.tourResults).toEqual([{ result_id: 1 }])
    })

    it('clears the warning only once every part of a refresh succeeds', async () => {
        mockApi()
        const stats = useStatsStore()
        await stats.load('t1')

        vi.spyOn(api, 'results').mockRejectedValue(new Error('offline'))
        await stats.refresh()
        expect(stats.syncError).toBe(true)

        vi.spyOn(api, 'results').mockResolvedValue({ data: [{ result_id: 2 }] } as any)
        await stats.refresh()
        expect(stats.syncError).toBe(false)
        expect(stats.tourResults).toEqual([{ result_id: 2 }])
    })

    it('still shows the error page when the first load fails', async () => {
        mockApi()
        vi.spyOn(api, 'results').mockRejectedValue(new Error('offline'))
        const stats = useStatsStore()
        await stats.load('t1')

        expect(stats.apiError).toBe(true)
        expect(stats.syncError).toBe(false)
    })

    it('starts clean when another tournament is opened', async () => {
        mockApi()
        const stats = useStatsStore()
        await stats.load('t1')
        vi.spyOn(api, 'results').mockRejectedValue(new Error('offline'))
        await stats.refresh()

        vi.spyOn(api, 'results').mockResolvedValue({ data: [] } as any)
        await stats.load('t2')
        expect(stats.syncError).toBe(false)
    })

    it('ignores a late reply for a tournament that is no longer open', async () => {
        mockApi()
        const stats = useStatsStore()
        await stats.load('t1')

        let replyForOldTournament = (_: unknown) => {}
        vi.spyOn(api, 'results').mockReturnValueOnce(new Promise((resolve) => { replyForOldTournament = resolve }) as any)
        const slowRefresh = stats.refresh()

        vi.spyOn(api, 'results').mockResolvedValue({ data: [{ result_id: 'new' }] } as any)
        await stats.load('t2')

        replyForOldTournament({ data: [{ result_id: 'old' }] })
        await slowRefresh
        expect(stats.tourResults).toEqual([{ result_id: 'new' }])
    })
})
