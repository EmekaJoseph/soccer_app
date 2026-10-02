import { ref } from 'vue'
import { defineStore } from 'pinia'
import api from '@/store/axiosManager'

export const MATCH_STAGES = ['Friendly', 'Group_Stage', 'Round_of_32', 'Round_of_16', 'Knock_Out', 'Quarter_Final', 'Semi_Final', 'Third_place', 'Final'] as const
export const GROUPS = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'] as const

/** Data for the signed-in dashboard. Every loader records `apiError` instead of throwing. */
export const useUserDataStore = defineStore('dataStore', () => {
  const apiError = ref(false)
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

  async function load<T>(request: () => Promise<{ data: T }>, assign: (data: T) => void) {
    try {
      assign((await request()).data)
      apiError.value = false
    } catch {
      apiError.value = true
    }
  }

  const getTournaments = () => load(api.getTournaments, (d: any[]) => (tournaments.value = d))
  const getTournamentTeams = (id: string) => load(() => api.getTournamentTeams(id), (d: any[]) => (tournamentTeams.value = d))
  const getTournamentMatches = (id: string) => load(() => api.getTournamentMatches(id), (d: any[]) => (tournamentMatches.value = d))
  const getTournamentResults = (id: string) => load(() => api.getTournamentResults(id), (d: any[]) => (tournamentResults.value = d))
  const getLiveMatchesByUser = (id: string) => load(() => api.getLiveMatchesByUser(id), (d: any[]) => (tournamentLive.value = d))
  const getPlayers = (id: string) => load(() => api.getPlayers(id), (d: any[]) => (tournamentPlayers.value = d))
  const getPredictions = (id: string) => load(() => api.getPredictions(id), (d: any[]) => (predictions.value = d))
  const getDashboardFigures = () => load(api.dashboard, (d: Record<string, number>) => (dashboardFigures.value = d))
  const getSubUsers = () => load(api.subUsersList, (d: any[]) => (subUsers.value = d))
  const getFeedbacks = () => load(api.getFeedbacks, (d: any[]) => (feedback.value = d))

  return {
    apiLoading,
    apiError,
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
