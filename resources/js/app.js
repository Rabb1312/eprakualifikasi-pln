import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import App from './App.vue';
import axios from 'axios'

const app = createApp(App);
app.use(createPinia());
app.use(router);
app.mount('#app');
axios.defaults.baseURL = '/'
const token = localStorage.getItem('token');
if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}
// ✅ TAMBAHKAN: Enhanced axios configuration
axios.defaults.timeout = 15000; // 15 seconds timeout
axios.defaults.baseURL = 'http://eprakualifikasi-pln.test';

// ✅ TAMBAHKAN: Request interceptor to prevent CORS/timing issues
axios.interceptors.request.use(
    (config) => {
        // Add auth header if token exists
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        
        // Add cache busting for API calls
        if (config.url?.includes('/api/')) {
            const separator = config.url.includes('?') ? '&' : '?';
            config.url += `${separator}t=${Date.now()}`;
        }
        
        return config;
    },
    (error) => Promise.reject(error)
);

// ✅ TAMBAHKAN: Response interceptor for better error handling
axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);