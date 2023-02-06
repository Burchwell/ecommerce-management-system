import router from './routes/index';
import axios from 'axios';

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json"
}

let http = axios.create({
    'baseUrl': process.env.VUE_APP_BASE_URL,
    headers: headers
});

http.interceptors.response.use(function (config) {
    const token = localStorage.getItem('access_token');
    console.log(token);
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config
}, error => {
    let path = '/error';
    switch (error.response.status) {
        case 401:
            path = '/login'
            localStorage.removeItem('access_token');
            localStorage.removeItem('user');
            router.push(path);
            return Promise.reject(error)
        case 404:
            path = '/404-PageNotFound'
            router.push(path);
            return Promise.reject(error)
        default:
            break;
    }
});

export default http;
