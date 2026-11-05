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
  /** A refresh failed: what is on screen is still valid, but may be out of date. */
  const syncError = ref(false)
  let initialLoadDone = false

  /** Returns false when the request failed. Late replies for a tournament no longer open are dropped. */
  async function fetchInto(target: { value: any }, request: () => Promise<{ data: any }>) {
    const requestedFor = tour_id.value
    try {
      const { data } = await request()
      if (requestedFor === tour_id.value) target.value = data
      return true
    } catch {
      if (requestedFor !== tour_id.value) return true
      if (initialLoadDone) syncError.value = true
      else apiError.value = true
      return false
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
    const outcomes = await Promise.all([getStandings(), getMatches(), getResults(), getLiveMatches(), getTourTeamsInfo()])
    if (initialLoadDone && outcomes.every(Boolean)) syncError.value = false
  }

  /** Load a tournament from scratch (also clears whatever tournament was open before). */
  async function load(id: string) {
    tour_id.value = id
    apiLoading.value = true
    apiError.value = false
    notFound.value = false
    syncError.value = false
    initialLoadDone = false
    for (const list of [tourStandings, tourResults, tourMatches, tourTeamsInfo, tourPlayers, tourLives]) list.value = []

    try {
      const { data } = await api.tour_data(id)
      tour_title.value = data.tour_title
      tour_type.value = data.tour_type
      tour_logo.value = data.tour_logo
      tour_desc.value = data.tour_desc
      await Promise.all([refresh(), getPlayers()])
      initialLoadDone = !apiError.value
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
    syncError,
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
