import { describe, expect, it } from 'vitest'
import { groupByMatchDay, kickoffTime, matchDayLabel, NO_DATE_LABEL } from '@/views/StatsView/matchDates'

describe('match dates', () => {
    it('groups neighbouring matches on the same day and keeps API order', () => {
        const matches = [
            { id: 1, at: '2026-11-07T10:00:00' },
            { id: 2, at: '2026-11-07T15:00:00' },
            { id: 3, at: '2026-11-05T15:00:00' },
        ]

        const groups = groupByMatchDay(matches, (m) => m.at)

        expect(groups.map((g) => g.items.map((m) => m.id))).toEqual([[1, 2], [3]])
        expect(groups[0]!.label).toBe(matchDayLabel('2026-11-07T10:00:00'))
    })

    it('handles missing and malformed dates instead of printing Invalid Date', () => {
        const groups = groupByMatchDay([{ at: null }, { at: 'not a date' }], (m) => m.at)

        expect(groups).toHaveLength(1)
        expect(groups[0]!.label).toBe(NO_DATE_LABEL)
        expect(kickoffTime(null)).toBe('TBC')
        expect(kickoffTime('not a date')).toBe('TBC')
    })
})
