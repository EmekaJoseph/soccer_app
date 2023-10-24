import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import api from '@/store/axiosManager'

export const useUserDataStore = defineStore('dataStore', () => {
  const apiError = ref<boolean>(false)
  const apiLoading = ref<boolean>(true)
  const tournaments = ref<any>([])
  const tournamentTeams = ref<any>([])
  const tournamentShedules = ref<any>([])
  const tournamentResults = ref<any>([])
  const tournamentLive = ref<any>([])
  const subUsers = ref<any>([])
  const predictions = ref<any>([])
  const match_stages = ref<any>(['Friendly', 'Group_Stage', 'Round_of_32', 'Round_of_16', 'Knock_Out', 'Quarter_Final', 'Semi_Final', 'Third_place', 'Final'])
  const valid_groups = ref<any>(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'])

  async function getTournaments() {
    try {
      let resp = await api.getTournaments()
      tournaments.value = (resp.status == 200) ? resp.data : [];
    } catch (error) {
      // console.log(error);
    }
  }

  async function getTournamentTeams(id: string) {
    try {
      let resp = await api.getTournamentTeams(id)
      tournamentTeams.value = (resp.status == 200) ? resp.data : [];
    } catch (error) {
      // console.log(error);
    }
  }

  async function getTournamentSchedules(id: string) {
    try {
      let resp = await api.getTournamentSchedules(id)
      tournamentShedules.value = (resp.status == 200) ? resp.data : [];
    } catch (error) {
      // console.log(error);
    }
  }

  async function getTournamentResults(id: string) {
    try {
      let resp = await api.results(id)
      tournamentResults.value = (resp.status == 200) ? resp.data : [];
    } catch (error) {
      // console.log(error);
    }
  }

  async function getLiveMatchesByUser(id: string) {
    try {
      let resp = await api.getLiveMatchesByUser(id)
      tournamentLive.value = (resp.status == 200) ? resp.data : [];
    } catch (error) {
      // console.log(error);
    }
  }

  async function getPredictions(id: string) {
    try {
      let resp = await api.getPredictions(id)
      predictions.value = (resp.status == 200) ? resp.data : [];
    } catch (error) {
      // console.log(error);
    }
  }


  async function getSubUsers() {
    try {
      let resp = await api.subUsersList()
      subUsers.value = resp.data;
      // console.log(resp);

    } catch (error) {
      // console.log(error);
    }
  }

  return {
    apiLoading,
    apiError,
    getTournaments,
    getTournamentTeams,
    getTournamentSchedules,
    getTournamentResults,
    getLiveMatchesByUser,
    getPredictions,
    getSubUsers,
    tournaments,
    tournamentTeams,
    tournamentShedules,
    tournamentResults,
    tournamentLive,
    match_stages,
    valid_groups,
    predictions,
    subUsers,
  }
})
