import { ref } from 'vue'
import { defineStore } from 'pinia'
import api from '@/store/axiosManager'

export const MATCH_STAGES = ['Friendly', 'Group_Stage', 'Round_of_32', 'Round_of_16', 'Knock_Out', 'Quarter_Final', 'Semi_Final', 'Third_place', 'Final'] as const
export const GROUPS = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'] as const

/** Data for the signed-in dashboard. Every loader records `apiError` instead of throwing. */
export const useUserDataStore = defineStore('dataStore', () => {
  /** Some request failed since the page last (re)loaded. */
  const apiError = ref(false)
  /** The page's own data never arrived, so there is nothing useful to show. */
  const pageError = ref(false)
  const apiLoading = ref(true)
  const tournaments = ref<any[]>([])
  const tournamentTeams = ref<any[]>([])
  const tournamentMatches = ref<any[]>([])
  const tournamentResults = ref<any[]>([])
  const tournamentLive = ref<any[]>([])
  const tournamentPlayers = ref<any[]>([])
  const subUsers = ref<any[]>([])
  const dashboardFigures = ref<Record<string, number> | null>(null)
  const predictions = ref<any[]>([])
  const feedback = ref<any[]>([])
  const match_stages = ref<readonly string[]>(MATCH_STAGES)
  const valid_groups = ref<readonly string[]>(GROUPS)

  // Only the newest request for each kind of data may fill it: admins can switch tournaments mid-load.
  const newestRequest = new Map<string, number>()

  async function load<T>(key: string, request: () => Promise<{ data: T }>, assign: (data: T) => void) {
    const ticket = (newestRequest.get(key) ?? 0) + 1
    newestRequest.set(key, ticket)
    try {
      const { data } = await request()
      if (newestRequest.get(key) === ticket) assign(data)
    } catch {
      if (newestRequest.get(key) === ticket) apiError.value = true
    }
  }

  /** Forget everything on sign-out, so the next account never sees this one's data. */
  function reset() {
    newestRequest.clear()
    for (const list of [tournaments, tournamentTeams, tournamentMatches, tournamentResults, tournamentLive, tournamentPlayers, subUsers, predictions, feedback]) list.value = []
    dashboardFigures.value = null
    apiError.value = false
    pageError.value = false
  }

  const getTournaments = () => load('tournaments', api.getTournaments, (d: any[]) => (tournaments.value = d))
  const getTournamentTeams = (id: string) => load('teams', () => api.getTournamentTeams(id), (d: any[]) => (tournamentTeams.value = d))
  const getTournamentMatches = (id: string) => load('matches', () => api.getTournamentMatches(id), (d: any[]) => (tournamentMatches.value = d))
  const getTournamentResults = (id: string) => load('results', () => api.getTournamentResults(id), (d: any[]) => (tournamentResults.value = d))
  const getLiveMatchesByUser = (id: string) => load('live', () => api.getLiveMatchesByUser(id), (d: any[]) => (tournamentLive.value = d))
  const getPlayers = (id: string) => load('players', () => api.getPlayers(id), (d: any[]) => (tournamentPlayers.value = d))
  const getPredictions = (id: string) => load('predictions', () => api.getPredictions(id), (d: any[]) => (predictions.value = d))
  const getDashboardFigures = () => load('dashboard', api.dashboard, (d: Record<string, number>) => (dashboardFigures.value = d))
  const getSubUsers = () => load('subUsers', api.subUsersList, (d: any[]) => (subUsers.value = d))
  const getFeedbacks = () => load('feedback', api.getFeedbacks, (d: any[]) => (feedback.value = d))

  return {
    reset,
    apiLoading,
    apiError,
    pageError,
    getTournaments,
    getTournamentTeams,
    getTournamentMatches,
    getTournamentResults,
    getLiveMatchesByUser,
    getPlayers,
    getPredictions,
    getSubUsers,
    getDashboardFigures,
    getFeedbacks,
    tournaments,
    tournamentTeams,
    tournamentMatches,
    tournamentResults,
    tournamentLive,
    tournamentPlayers,
    match_stages,
    valid_groups,
    predictions,
    subUsers,
    dashboardFigures,
    feedback,
  }
})
