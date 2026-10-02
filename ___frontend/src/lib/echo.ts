import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

export interface LiveScorePayload {
    tour_id: string
    live_id: number
    results: {
        home_team_score: number
        away_team_score: number
        curr_time: number
        isPaused: boolean
    }
}

let echo: Echo<'pusher'> | null = null

/** Lazily connect to Pusher; returns null when no key is configured (e.g. local dev). */
export function getEcho(): Echo<'pusher'> | null {
    if (echo) return echo

    const key = import.meta.env.VITE_WEBSOCKET_KEY
    if (!key) return null

    echo = new Echo({
        broadcaster: 'pusher',
        client: new Pusher(key, {
            cluster: import.meta.env.VITE_PUSHER_CLUSTER || 'mt1',
            forceTLS: true,
        }),
    })

    return echo
}

export interface TournamentLiveHandlers {
    started: (e: LiveScorePayload) => void
    updated: (e: LiveScorePayload) => void
    ended: (e: LiveScorePayload) => void
}

/**
 * Subscribe to the public live-score channel of one tournament.
 * Returns an unsubscribe function.
 */
export function listenToTournament(tourId: string, handlers: TournamentLiveHandlers): () => void {
    const client = getEcho()
    if (!client) return () => {}

    const name = `tournament.${tourId}`
    // Leading dot: the server uses broadcastAs(), so names are not namespaced.
    client.channel(name)
        .listen('.live.started', handlers.started)
        .listen('.live.updated', handlers.updated)
        .listen('.live.ended', handlers.ended)

    return () => client.leave(name)
}
