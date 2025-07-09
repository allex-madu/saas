import "animate.css";
import "flatpickr/dist/flatpickr.css";
import "sweetalert2/dist/sweetalert2.min.css";
import { createApp } from "vue";
import "simplebar-vue/dist/simplebar.min.css";
import VueFlatPickr from "vue-flatpickr-component";
import VueGoodTablePlugin from "vue-good-table-next";
import "vue-good-table-next/dist/vue-good-table-next.css";
import VueSweetalert2 from "vue-sweetalert2";
import VueTippy from "vue-tippy";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import VueApexCharts from "vue3-apexcharts";
import VueClickAway from "vue3-click-away";
import App from "./App.vue";
import "./assets/scss/auth.scss";
import "./assets/scss/tailwind.scss";
import router from "./router";
import VCalendar from "v-calendar";
import { createPinia } from "pinia";
import "v-calendar/dist/style.css";
//import { makeServer } from "./server";
import { VueQueryPlugin } from "@tanstack/vue-query";
import axios from 'axios';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'


// perfect scrollbar
import PerfectScrollbar from "vue3-perfect-scrollbar";
import "vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css";

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

// vue use
const app = createApp(App)
  .use(pinia)
  .use(VueSweetalert2)
  .use(Toast, {
    toastClassName: "dashcode-toast",
    bodyClassName: "dashcode-toast-body",
  })
  .use(router)
  .use(VueClickAway)
  .use(VueTippy)
  .use(VueFlatPickr)
  .use(VueGoodTablePlugin)
  .use(VueApexCharts)
  .use(PerfectScrollbar)
  .use(VCalendar);

app.config.globalProperties.$store = {};

// Configuração do Axios
axios.defaults.baseURL = 'http://localhost:8080'
axios.defaults.withCredentials = true;

// Garante envio automático do token CSRF como header X-XSRF-TOKEN
axios.interceptors.request.use(config => {
  const token = getCookie('XSRF-TOKEN')
  if (token) {
    config.headers['X-XSRF-TOKEN'] = decodeURIComponent(token)
  }
  return config
})

// Função auxiliar para pegar o cookie
function getCookie(name) {
  const match = document.cookie.match(new RegExp('(^|;\\s*)(' + name + ')=([^;]*)'))
  return match ? match[3] : null
}


app.mount("#app");
app.use(VueQueryPlugin);

import { useThemeSettingsStore } from "@/store/themeSettings";
const themeSettingsStore = useThemeSettingsStore();
if (localStorage.users === undefined) {
  let users = [
    {
      name: "dashcode",
      email: "dashcode@gmail.com",
      password: "dashcode",
    },
  ];
  localStorage.setItem("users", JSON.stringify(users));
}

// check localStorage theme for dark light bordered
if (localStorage.theme === "dark") {
  document.body.classList.add("dark");
  themeSettingsStore.theme = "dark";
  themeSettingsStore.isDark = true;
} else {
  document.body.classList.add("light");
  themeSettingsStore.theme = "light";
  themeSettingsStore.isDark = false;
}
if (localStorage.semiDark === "true") {
  document.body.classList.add("semi-dark");
  themeSettingsStore.semidark = true;
  themeSettingsStore.semiDarkTheme = "semi-dark";
} else {
  document.body.classList.add("semi-light");
  themeSettingsStore.semidark = false;
  themeSettingsStore.semiDarkTheme = "semi-light";
}
// check loacl storege for menuLayout
if (localStorage.menuLayout === "horizontal") {
  themeSettingsStore.menuLayout = "horizontal";
} else {
  themeSettingsStore.menuLayout = "vertical";
}

// check skin  for localstorage
if (localStorage.skin === "bordered") {
  themeSettingsStore.skin = "bordered";
  document.body.classList.add("skin--bordered");
} else {
  themeSettingsStore.skin = "default";
  document.body.classList.add("skin--default");
}
// check direction for localstorage
if (localStorage.direction === "true") {
  themeSettingsStore.direction = true;
  document.documentElement.setAttribute("dir", "rtl");
} else {
  themeSettingsStore.direction = false;
  document.documentElement.setAttribute("dir", "ltr");
}

// Check if the monochrome mode is set or not
if (localStorage.getItem("monochrome") !== null) {
  themeSettingsStore.monochrome = true;
  document.getElementsByTagName("html")[0].classList.add("grayscale");
}
// fake server
//makeServer();
