import { ref } from 'vue'
import { defineStore } from 'pinia'
import { useOnline } from '@vueuse/core'
import api from '@/store/axiosManager'
import type { LiveScorePayload } from '@/lib/echo'

/** Everything shown on the public stats page of one tournament. */
export const useStatsStore = defineStore('stats', () => {
  const isOnline = useOnline()
  const tour_id = ref('')
  const tour_title = ref('')
  const tour_type = ref<'cup' | 'league' | ''>('')
  const tour_logo = ref<string | null>(null)
  const tour_desc = ref<string | null>(null)
  const tourStandings = ref<any[]>([])
  const tourResults = ref<any[]>([])
  const tourMatches = ref<any[]>([])
  const tourTeamsInfo = ref<any[]>([])
  const tourPlayers = ref<any[]>([])
  const tourLives = ref<any[]>([])
  const apiError = ref(false)
  const notFound = ref(false)
  const apiLoading = ref(true)

  async function fetchInto(target: { value: any }, request: () => Promise<{ data: any }>) {
    try {
      target.value = (await request()).data
    } catch {
      apiError.value = true
    }
  }

  const getStandings = () => fetchInto(tourStandings, () => api.standings(tour_id.value))
  const getResults = () => fetchInto(tourResults, () => api.results(tour_id.value))
  const getMatches = () => fetchInto(tourMatches, () => api.matches(tour_id.value))
  const getLiveMatches = () => fetchInto(tourLives, () => api.getLiveMatches(tour_id.value))
  const getTourTeamsInfo = () => fetchInto(tourTeamsInfo, () => api.infomationCenter(tour_id.value))
  const getPlayers = () => fetchInto(tourPlayers, () => api.publicPlayers(tour_id.value))

  /** Refresh the panels that change as matches are played. */
  async function refresh() {
    await Promise.all([getStandings(), getMatches(), getResults(), getLiveMatches(), getTourTeamsInfo()])
  }

  /** Load a tournament from scratch (also clears whatever tournament was open before). */
  async function load(id: string) {
    tour_id.value = id
    apiLoading.value = true
    apiError.value = false
    notFound.value = false
    for (const list of [tourStandings, tourResults, tourMatches, tourTeamsInfo, tourPlayers, tourLives]) list.value = []

    try {
      const { data } = await api.tour_data(id)
      tour_title.value = data.tour_title
      tour_type.value = data.tour_type
      tour_logo.value = data.tour_logo
      tour_desc.value = data.tour_desc
      await Promise.all([refresh(), getPlayers()])
    } catch (error: any) {
      notFound.value = error?.response?.status === 404
      apiError.value = true
    } finally {
      apiLoading.value = false
    }
  }

  /** Apply a live score push; returns true when a goal was scored. */
  function applyLiveUpdate(e: LiveScorePayload): boolean {
    const live = tourLives.value.find((x) => x.live_id === e.live_id)
    if (!live) {
      getLiveMatches()
      return false
    }

    const goal = e.results.home_team_score > live.home_team_score || e.results.away_team_score > live.away_team_score
    Object.assign(live, e.results)
    return goal
  }

  function removeLive(liveId: number) {
    tourLives.value = tourLives.value.filter((x) => x.live_id !== liveId)
  }

  return {
    apiError,
    apiLoading,
    notFound,
    isOnline,
    tour_id,
    tour_title,
    tour_type,
    tour_logo,
    tour_desc,
    tourStandings,
    tourResults,
    tourMatches,
    tourTeamsInfo,
    tourPlayers,
    tourLives,
    load,
    refresh,
    getStandings,
    getResults,
    getMatches,
    getLiveMatches,
    getTourTeamsInfo,
    getPlayers,
    applyLiveUpdate,
    removeLive,
  }
})
