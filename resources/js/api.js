import axios from 'axios'
import router from '@/router'
import store from "@/store"

let path = 'error'

const api = axios.create({
    baseURL: '/api/v1',
    headers: {
        "Accept": "application/json",
        "Content-Type": "application/json",
    }
})

let token  = localStorage.getItem('access_token')

api.interceptors.request.use(config => {
    return config
}, error => {
    store.state.loading = false
    return Promise.reject(error)
})

api.interceptors.response.use(config => {
    store.state.loading = false
    return config
}, error => {
    if (typeof error.response !== 'undefined') {
        switch (error.response.status) {
            case 401:
                path = '/login'
                break
            case 500:
                path = '/500'
                break
        }

    }

    store.state.loading = false
    router.push(path)
    return Promise.reject(error)
})

export default api
