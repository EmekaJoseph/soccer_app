import { describe, expect, it, vi } from 'vitest'
import { AxiosError, AxiosHeaders } from 'axios'
import { apiErrorMessage } from '@/store/axiosManager'
import fx from '@/store/useFunctions'

const axiosError = (status: number | null, data?: unknown) => {
    const response = status === null ? undefined : {
        status, data, statusText: '', headers: {}, config: { headers: new AxiosHeaders() },
    }
    return new AxiosError('failed', 'ERR', undefined, undefined, response as any)
}

describe('apiErrorMessage', () => {
    it('prefers the first validation error', () => {
        expect(apiErrorMessage(axiosError(422, {
            message: 'The given data was invalid.',
            errors: { team_name: ['A team with this name already exists in the tournament.'] },
        }))).toBe('A team with this name already exists in the tournament.')
    })

    it('falls back to the server message', () => {
        expect(apiErrorMessage(axiosError(409, { message: 'This match is already scheduled.' }))).toBe('This match is already scheduled.')
    })

    it('explains network failures', () => {
        expect(apiErrorMessage(axiosError(null))).toMatch(/network/i)
    })

    it('handles non-axios errors', () => {
        expect(apiErrorMessage(new Error('boom'), 'fallback')).toBe('fallback')
    })
})

describe('useFunctions', () => {
    it('labels match stages', () => {
        expect(fx.stageLabel('Quarter_Final')).toBe('Quarter Final')
        expect(fx.stageLabel(null, '—')).toBe('—')
    })

    it('round-trips datetime-local values through ISO', () => {
        const local = fx.toDatetimeLocal('2030-05-01T15:30:00.000Z')
        expect(local).toMatch(/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/)
        expect(new Date(local).toISOString()).toBe('2030-05-01T15:30:00.000Z')
    })

    it('debounces and can cancel', () => {
        vi.useFakeTimers()
        const spy = vi.fn()
        const debounced = fx.debounce(spy, 100)
        debounced(1)
        debounced(2)
        vi.advanceTimersByTime(100)
        expect(spy).toHaveBeenCalledTimes(1)
        expect(spy).toHaveBeenCalledWith(2)

        debounced(3)
        debounced.cancel()
        vi.advanceTimersByTime(200)
        expect(spy).toHaveBeenCalledTimes(1)
        vi.useRealTimers()
    })

    it('resolves upload paths against the API host', () => {
        expect(fx.resolvePhotoSrc(null)).toBe('')
        expect(fx.resolvePhotoSrc('team_badges/x.webp')).toMatch(/\/team_badges\/x\.webp$/)
    })
})
