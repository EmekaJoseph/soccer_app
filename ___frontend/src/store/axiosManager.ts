import axios from 'axios'
// @ts-ignore
import Cookies from 'js-cookie';

// const hostURL = 'http://127.0.0.1:8000' //dev
// // const hostURL = '' //build

const hostURL = import.meta.env.VITE_API_URL;
const apiURL = `${hostURL}/api/`;

const $instance = axios.create({
    baseURL: apiURL,
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json;charset=UTF-8;text/json',
        withCredentials: true,
    },
})

const $instanceForm = axios.create({
    baseURL: apiURL,
    headers: {
        Accept: 'application/json',
        withCredentials: true,
        'Content-Type': 'multipart/form-data',
    },
})

// create interceptor for renewing token
$instance.interceptors.request.use(
    (config: any) => {
        const token = Cookies.get(import.meta.env.VITE_TOKEN_NAME);
        if (token) config.headers.Authorization = `Bearer ${token}`;
        return config;
    }
);

$instanceForm.interceptors.request.use(
    (config: any) => {
        const token = Cookies.get(import.meta.env.VITE_TOKEN_NAME);
        if (token) config.headers.Authorization = `Bearer ${token}`;
        return config;
    }
);

export default {

    webSocketHost() { return '127.0.0.1' }, //or window.location.hostname
    webSocketKey() { return import.meta.env.VITE_WEBSOCKET_KEY },

    standings(tour_id: string) {
        return $instance.get(`view/standings/${tour_id}`,)
    },

    results(tour_id: string) {
        return $instance.get(`view/results/${tour_id}`,)
    },

    matches(tour_id: string) {
        return $instance.get(`view/matches/${tour_id}`,)
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

    dashboard() {
        return $instance.get(`dashboard`,)
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


    createTournament(data: FormData) {
        return $instanceForm.post(`createTournament`, data)
    },

    updateTournament(data: FormData) {
        return $instanceForm.post(`updateTournament`, data)
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

    getTournamentMatches(tour_id: string) {
        return $instance.get(`match?tour_id=${tour_id}`)
    },

    createMatch(data: object) {
        return $instance.post(`match`, JSON.stringify(data))
    },

    deleteMatch(team_id: any) {
        return $instance.delete(`match/${team_id}`)
    },


    saveResult(data: object) {
        return $instance.post(`save_result`, JSON.stringify(data))
    },

    undoResult(data: any) {
        return $instance.post(`undo_result/${data.result_id}`, JSON.stringify(data))
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

    endLiveMatchAndSave(live_id: string) {
        return $instance.get(`endLiveMatchAndSave/${live_id}`)
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
