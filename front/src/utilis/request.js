import axios from 'axios';
import { getToken, setToken } from './auth';

// Create axios instance
const service = axios.create({
    baseURL: process.env.MIX_BASE_API,
    timeout: 10000, // Request timeout
});

// Request intercepter
service.interceptors.request.use(
    config => {
        const token = getToken();
        if (token) {
            config.headers['Authorization'] = 'Bearer ' + getToken(); // Set JWT token
        }
        return config;
    },
    error => {
        // Do something with request error
        Promise.reject(error);
    }
);

// response pre-processing
service.interceptors.response.use(
    response => {
        if (response.headers.authorization) {
            setToken(response.headers.authorization);
            response.data.token = response.headers.authorization;
        }

        return response.data;
    },
    error => {
        let message = error.message;
        if (error.response.data && error.response.data.message) {
            message = error.response.data.message;
        }

        return Promise.reject(error);
    },
);

export default service;
