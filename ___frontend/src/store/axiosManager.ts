import axios, { isAxiosError } from 'axios'
import Cookies from 'js-cookie'

export const hostURL: string = import.meta.env.VITE_API_URL ?? ''

const http = axios.create({
    baseURL: `${hostURL}/api/`,
    headers: { Accept: 'application/json' },
})

http.interceptors.request.use((config) => {
    const token = Cookies.get(import.meta.env.VITE_TOKEN_NAME)
    if (token) config.headers.Authorization = `Bearer ${token}`
    return config
})

// An expired/revoked token on a signed-in page: drop it and go back to login.
http.interceptors.response.use(undefined, (error) => {
    if (isAxiosError(error) && error.response?.status === 401 && Cookies.get(import.meta.env.VITE_TOKEN_NAME)) {
        Cookies.remove(import.meta.env.VITE_TOKEN_NAME)
        localStorage.removeItem('socc_user')
        if (window.location.pathname.startsWith('/user')) window.location.assign('/login')
    }
    return Promise.reject(error)
})

/** A readable message for any failed request (validation, conflict, permission, network). */
export function apiErrorMessage(error: unknown, fallback = 'Something went wrong, please try again.'): string {
    if (!isAxiosError(error)) return fallback
    if (!error.response) return 'Network error, check your internet connection.'

    const data = error.response.data as { message?: string; errors?: Record<string, string[]> } | undefined
    const firstFieldError = data?.errors ? Object.values(data.errors)[0]?.[0] : undefined

    return firstFieldError ?? data?.message ?? fallback
}

/** Laravel cannot read multipart PUT bodies, so file uploads are POSTed with _method=PUT. */
function formData(fields: Record<string, unknown>, method?: 'PUT'): FormData {
    const form = new FormData()
    if (method) form.append('_method', method)
    for (const [key, value] of Object.entries(fields)) {
        if (value === undefined || value === null) continue
        form.append(key, value instanceof Blob ? value : String(value))
    }
    return form
}

export type Id = string | number

export default {
    // ---------------------------------------------------------------- public stats page
    tour_data: (tour: Id) => http.get(`view/tournaments/${tour}`),
    standings: (tour: Id) => http.get(`view/tournaments/${tour}/standings`),
    results: (tour: Id) => http.get(`view/tournaments/${tour}/results`),
    matches: (tour: Id) => http.get(`view/tournaments/${tour}/matches`),
    getLiveMatches: (tour: Id) => http.get(`view/tournaments/${tour}/live`),
    infomationCenter: (tour: Id) => http.get(`view/tournaments/${tour}/teams`),
    publicPlayers: (tour: Id, params: { search?: string; team_id?: string } = {}) =>
        http.get(`view/tournaments/${tour}/players`, { params }),
    savePrediction: (tour: Id, data: object) => http.post(`view/tournaments/${tour}/predictions`, data),
    sendFeedBack: (tour: Id, data: object) => http.post(`view/tournaments/${tour}/feedback`, data),

    // ---------------------------------------------------------------- account
    login: (data: { email: string; password: string }) => http.post('login', data),
    register: (data: object) => http.post('register', data),
    forgotPassword: (email: string) => http.post('forgot-password', { email }),
    resetPassword: (data: object) => http.post('reset-password', data),
    logout: () => http.post('logout'),
    me: () => http.get('me'),
    updateProfile: (data: object) => http.put('me', data),
    changePassword: (data: object) => http.put('me/password', data),

    dashboard: () => http.get('dashboard'),
    getFeedbacks: () => http.get('feedback'),

    subUsersList: () => http.get('sub-users'),
    createSubUser: (data: object) => http.post('sub-users', data),
    deleteSubUser: (id: Id) => http.delete(`sub-users/${id}`),

    // ---------------------------------------------------------------- tournaments
    getTournaments: () => http.get('tournaments'),
    createTournament: (fields: Record<string, unknown>) => http.post('tournaments', formData(fields)),
    updateTournament: (id: Id, fields: Record<string, unknown>) => http.post(`tournaments/${id}`, formData(fields, 'PUT')),
    deleteTournament: (id: Id) => http.delete(`tournaments/${id}`),

    // ---------------------------------------------------------------- teams & players
    getTournamentTeams: (tour: Id) => http.get(`tournaments/${tour}/teams`),
    createTeam: (fields: Record<string, unknown>) => http.post('teams', formData(fields)),
    updateTeam: (id: Id, fields: Record<string, unknown>) => http.post(`teams/${id}`, formData(fields, 'PUT')),
    deleteTeam: (id: Id) => http.delete(`teams/${id}`),

    getPlayers: (tour: Id) => http.get(`tournaments/${tour}/players`),
    createPlayer: (fields: Record<string, unknown>) => http.post('players', formData(fields)),
    updatePlayer: (id: Id, fields: Record<string, unknown>) => http.post(`players/${id}`, formData(fields, 'PUT')),
    deletePlayer: (id: Id) => http.delete(`players/${id}`),

    // ---------------------------------------------------------------- matches & results
    getTournamentMatches: (tour: Id) => http.get(`tournaments/${tour}/matches`),
    createMatch: (data: object) => http.post('matches', data),
    updateMatch: (id: Id, data: object) => http.put(`matches/${id}`, data),
    deleteMatch: (id: Id) => http.delete(`matches/${id}`),

    getTournamentResults: (tour: Id) => http.get(`tournaments/${tour}/results`),
    saveResult: (data: object) => http.post('results', data),
    undoResult: (resultId: Id) => http.delete(`results/${resultId}`),

    // ---------------------------------------------------------------- live scoring
    startLiveMatch: (matchId: Id) => http.post('live', { match_id: matchId }),
    updateLiveMatch: (liveId: Id, data: object) => http.put(`live/${liveId}`, data),
    endLiveMatch: (liveId: Id, save: boolean) => http.post(`live/${liveId}/end`, { save }),
    getLiveMatchesByUser: (tour: Id) => http.get(`tournaments/${tour}/live`),
    getLiveMatchesForAdmin: (tour: Id) => http.get(`tournaments/${tour}/live/all`),

    // ---------------------------------------------------------------- predictions
    getPredictions: (tour: Id) => http.get(`tournaments/${tour}/predictions`),
    getWinnersByPrediction: (tour: Id, params: { first: string; second?: string; third?: string }) =>
        http.get(`tournaments/${tour}/predictions/winners`, { params }),
}
