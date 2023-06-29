import axios from 'axios'

const hostURL = 'http://127.0.0.1:8000' //dev
// const hostURL = '' //build

const apiURL = `${hostURL}/api/`

const $instance = axios.create({
    baseURL: apiURL,
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



    // USER

    login(data: object) {
        return $instance.post(`userLogin`, JSON.stringify(data))
    },

    logout() {
        return $instance.get(`userLogout`,)
    },



    createTournament(data: object) {
        return $instance.post(`createTournament`, JSON.stringify(data))
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

}