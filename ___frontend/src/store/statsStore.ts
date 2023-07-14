import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import api from '@/store/axiosManager'

export const useStatsStore = defineStore('stats', () => {
  const tour_id = ref<any>('')
  const tour_title = ref<any>('')
  const tour_type = ref<any>('')
  const tourStandings = ref<any[]>([])
  const tourResults = ref<any[]>([])
  const tourSchedules = ref<any[]>([])
  const tourLives = ref<any[]>([])
  const apiError = ref<boolean>(false)
  const statsLoaded = ref<boolean>(false)
  const apiLoading = ref<boolean>(true)
  // const doubleCount = computed(() => count.value * 2)


  async function getTourDetails() {
    tour_type.value = ''
    tour_title.value = ''
    try {
      apiError.value = false
      let resp = await api.tour_data(tour_id.value)
      if (resp.status == 200) {
        tour_title.value = resp.data.tour_title
        tour_type.value = resp.data.tour_type
      }
      // console.log(resp);
    } catch (error) {
      console.log(error);
    }
  }

  async function getStandings() {
    try {
      apiError.value = false
      let resp = await api.standings(tour_id.value)
      tourStandings.value = resp.data
      apiLoading.value = false
      // console.log(resp);

    } catch (error) {
      apiLoading.value = false
      apiError.value = true
      console.log(error);
    }
  }

  async function getResults() {
    try {
      apiError.value = false
      let resp = await api.results(tour_id.value)
      tourResults.value = resp.data
      apiLoading.value = false
      // console.log(resp);
    } catch (error) {
      apiLoading.value = false
      apiError.value = true
      console.log(error);

    }
  }

  async function getSchedules() {
    try {
      apiError.value = false
      let resp = await api.schedules(tour_id.value)
      tourSchedules.value = resp.data
      apiLoading.value = false
      // console.log(resp);
    } catch (error) {
      apiLoading.value = false
      apiError.value = true
    }
  }


  async function getLiveMatches() {
    try {
      let resp = await api.getLiveMatches(tour_id.value)
      tourLives.value = resp.data
      apiLoading.value = false
      // console.log(resp);
    } catch (error) {
      apiLoading.value = false
      apiError.value = true
    }
  }

  return {
    apiError,
    apiLoading,
    tour_title,
    tour_type,
    tour_id,
    tourStandings,
    tourResults,
    tourSchedules,
    tourLives,
    statsLoaded,
    getStandings,
    getResults,
    getSchedules,
    getTourDetails,
    getLiveMatches
  }
})
