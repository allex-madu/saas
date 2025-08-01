// CSS externos
import "animate.css"
import "flatpickr/dist/flatpickr.css"
import "sweetalert2/dist/sweetalert2.min.css"
import "simplebar-vue/dist/simplebar.min.css"
import "vue-good-table-next/dist/vue-good-table-next.css"
import "vue-toastification/dist/index.css"
import "v-calendar/dist/style.css"
import "vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css"

// SCSS internos
import "./assets/scss/auth.scss"
import "./assets/scss/tailwind.scss"

// Core Vue
import { createApp } from "vue"
import App from "./App.vue"
import router from "./router"
import { createPinia } from "pinia"

// Plugins Vue
import VueFlatPickr from "vue-flatpickr-component"
import VueGoodTablePlugin from "vue-good-table-next"
import VueSweetalert2 from "vue-sweetalert2"
import VueTippy from "vue-tippy";
import Toast from "vue-toastification"
import VueApexCharts from "vue3-apexcharts"
import VueClickAway from "vue3-click-away"
import VCalendar from "v-calendar"
import PerfectScrollbar from "vue3-perfect-scrollbar"
import { VueQueryPlugin } from "@tanstack/vue-query"

// Plugins App
import piniaPluginPersistedstate from "pinia-plugin-persistedstate"
import api from "@/plugins/axios";
import { setupThemeFromLocalStorage } from "@/utils/theme"

// Stores
import { useAuthStore } from "@/store/authStore"
import { useThemeSettingsStore } from "@/store/themeSettings"
import { useActiveBakeryStore } from '@/store/activeBakeryStore'

// Instância Pinia
const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)

// App instance
const app = createApp(App)
  .use(pinia)
  .use(router)
  .use(VueSweetalert2)
  .use(Toast, {
    toastClassName: "dashcode-toast",
    bodyClassName: "dashcode-toast-body",
  })
  .use(VueClickAway)
  .use(VueTippy)
  .use(VueFlatPickr)
  .use(VueGoodTablePlugin)
  .use(VueApexCharts)
  .use(PerfectScrollbar)
  .use(VCalendar)
  .use(VueQueryPlugin)

// Global store (evita erro com $store antigo)
app.config.globalProperties.$store = {}

// Store de autenticação
const auth = useAuthStore()

// Sincroniza logout entre abas
window.addEventListener("storage", (e) => {
  if (e.key === "activeUser" && !e.newValue) {
    auth.user = null;
    console.log("Logout detectado em outra aba. Limpando usuário local.")
  }
});

// Inicialização do app (tema, csrf, auth)
async function initializeApp() {
  try {
    await api.get("/sanctum/csrf-cookie");

    // Autenticação
    await auth.fetchUser();

    // Tema
    const themeSettingsStore = useThemeSettingsStore();
    setupThemeFromLocalStorage(themeSettingsStore);

    // Bakery ativa
    const activeBakeryStore = useActiveBakeryStore();
    await activeBakeryStore.fetchMyBakeries();
    activeBakeryStore.loadActiveBakeryFromStorage();

  } catch (error) {
    console.error("Erro durante a inicialização do aplicativo:", error);
  } finally {
    app.mount("#app");
  }
}

// Start
initializeApp();
