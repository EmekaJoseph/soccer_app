import axios from 'axios'

const hostURL = 'http://127.0.0.1:8000' //dev
// const hostURL = '' //build

const $instance = axios.create({
    baseURL: `${hostURL}/api/`,
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json;charset=UTF-8;text/json',
        withCredentials: true,
    },
})

// create interceptor for renewing token
$instance.interceptors.request.use(
    (config: any) => {
        const token = localStorage.getItem('dlam_cup_T');
        if (token) config.headers.Authorization = `Bearer ${token}`;
        return config;
    }
);

export default {

    webSocketHost() { return '127.0.0.1' }, //or window.location.hostname
    webSocketKey() { return '6ec0771b744a66d007f3' },

    standings(tour_id: string) {
        return $instance.get(`view/standings/${tour_id}`,)
    },

    results(tour_id: string) {
        return $instance.get(`view/results/${tour_id}`,)
    },

    schedules(tour_id: string) {
        return $instance.get(`view/schedules/${tour_id}`,)
    },

    tour_data(tour_id: string) {
        return $instance.get(`view/tour_data/${tour_id}`,)
    },

    getLiveMatches(tour_id: string) {
        return $instance.get(`view/live/${tour_id}`)
    },

    infomationCenter(tour_id: string) {
        return $instance.get(`view/infomationCenter/${tour_id}`)
    },



    savePrediction(data: any) {
        return $instance.post(`save_prediction`, JSON.stringify(data))
    },

    getPredictions(tour_id: any) {
        return $instance.get(`get_predictions?tour_id=${tour_id}`)
    },



    // USER

    login(data: object) {
        return $instance.post(`userLogin`, JSON.stringify(data))
    },

    logout() {
        return $instance.get(`userLogout`,)
    },

    subUsersList() {
        return $instance.get(`subUsersList`,)
    },

    createSubUser(data: object) {
        return $instance.post(`createSubUser`, JSON.stringify(data))
    },

    deleteSubUser(id: any) {
        return $instance.get(`deleteSubUser/${id}`)
    },


    createTournament(data: object) {
        return $instance.post(`createTournament`, JSON.stringify(data))
    },

    deleteTournament(id: any) {
        return $instance.get(`deleteTournament/${id}`)
    },


    getTournaments() {
        return $instance.get(`getTournaments`,)
    },

    getTournamentTeams(tour_id: string) {
        return $instance.get(`team?tour_id=${tour_id}`)
    },

    createTeam(data: object) {
        return $instance.post(`team`, JSON.stringify(data))
    },

    deleteTeam(team_id: any) {
        return $instance.delete(`team/${team_id}`)
    },

    getTournamentSchedules(tour_id: string) {
        return $instance.get(`schedule?tour_id=${tour_id}`)
    },

    createSchedule(data: object) {
        return $instance.post(`schedule`, JSON.stringify(data))
    },

    deleteSchedule(team_id: any) {
        return $instance.delete(`schedule/${team_id}`)
    },


    saveCupResult(data: object) {
        return $instance.post(`cup/save_result`, JSON.stringify(data))
    },

    saveLeagueResult(data: object) {
        return $instance.post(`league/save_result`, JSON.stringify(data))
    },

    undoSaveCupResult(data: any) {
        return $instance.post(`cup/undo_save_result/${data.result_id}`, JSON.stringify(data))
    },

    undoSaveLeagueResult(data: any) {
        return $instance.post(`league/undo_save_result/${data.result_id}`, JSON.stringify(data))
    },


    startLiveMatch(data: any) {
        return $instance.post(`startLiveMatch`, JSON.stringify(data))
    },

    updateLiveMatch(data: any) {
        return $instance.post(`updateLiveMatch/${data.live_id}`, JSON.stringify(data))
    },

    endLiveMatch(live_id: string) {
        return $instance.get(`endLiveMatch/${live_id}`)
    },

    getLiveMatchesByUser(tour_id: string) {
        return $instance.get(`getLiveMatchesByUser/${tour_id}`,)
    },

    getLiveMatchesForAdmin(tour_id: string) {
        return $instance.get(`getLiveMatchesForAdmin/${tour_id}`,)
    },

    getWinnersByPrediction(data: object) {
        return $instance.post(`getWinnersByPrediction`, JSON.stringify(data))
    },

    sendFeedBack(data: object) {
        return $instance.post(`sendFeedBack`, JSON.stringify(data))
    },

    getFeedbacks() {
        return $instance.get(`getFeedbacks`)
    }
}
