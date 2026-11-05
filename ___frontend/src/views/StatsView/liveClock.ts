const MAX_MINUTE = 200

/** The minute to show fans: the last one received, plus the time since, unless the match is paused. */
export function liveMinute(live: { curr_time?: number; isPaused?: boolean; syncedAt?: number }, now: number): number {
    const minute = Number(live.curr_time ?? 0)
    if (live.isPaused || !live.syncedAt) return minute

    const elapsed = Math.max(0, Math.floor((now - live.syncedAt) / 60_000))
    return Math.min(MAX_MINUTE, minute + elapsed)
}
