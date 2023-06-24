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


    // login(data: object) {
    //     return $instance.post(`user/login`, JSON.stringify(data))
    // },

    // logout() {
    //     return $instance.get(`user/logout`,)
    // },

    // register(data: object) {
    //     return $instance.post(`user/create`, JSON.stringify(data))
    // },

    // taskList() {
    //     return $instance.get(`task/list`)
    // },

    // addTask(data: object) {
    //     return $instance.post(`task/create`, JSON.stringify(data))
    // },

    // deleteTask(task_id: any) {
    //     return $instance.get(`task/delete/${task_id}`)
    // },

    // mark_as_complete(task_id: any) {
    //     return $instance.get(`task/mark_as_complete/${task_id}`)
    // },

    // completed_list() {
    //     return $instance.get(`task/completed_list`)
    // },

    // approve(task_id: any) {
    //     return $instance.get(`task/approve/${task_id}`)
    // },



    // app_reset() {
    //     return $instance.get(`app_reset`)
    // }
}
