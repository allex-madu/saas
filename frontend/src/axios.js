// import axios from 'axios';

// const api = axios.create({
//   baseURL: 'http://127.0.0.1:8080',
//   withCredentials: true,
// });

// // Captura o cookie manualmente e adiciona no header
// api.interceptors.request.use((config) => {
//   const matches = document.cookie.match(new RegExp('(^| )XSRF-TOKEN=([^;]+)'));
//   if (matches) {
//     config.headers['X-XSRF-TOKEN'] = decodeURIComponent(matches[2]);
//   }
//   return config;
// });

// export default api;
// /