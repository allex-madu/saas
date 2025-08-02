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

// Core
import { createApp } from "vue"
import App from "./App.vue"
import router from "./router"
import { createPinia } from "pinia"

// Plugins Vue
import VueFlatPickr from "vue-flatpickr-component"
import VueGoodTablePlugin from "vue-good-table-next"
import VueSweetalert2 from "vue-sweetalert2"
import VueTippy from "vue-tippy"
import Toast from "vue-toastification"
import VueApexCharts from "vue3-apexcharts"
import VueClickAway from "vue3-click-away"
import VCalendar from "v-calendar"
import PerfectScrollbar from "vue3-perfect-scrollbar"
import { VueQueryPlugin } from "@tanstack/vue-query"

// App plugins e utils
import piniaPluginPersistedstate from "pinia-plugin-persistedstate"
import api from "@/plugins/axios"
import { setupThemeFromLocalStorage } from "@/utils/theme"

// Stores
import { useAuthStore } from "@/store/authStore"
import { useThemeSettingsStore } from "@/store/themeSettings"

// Cria app e pinia
const app = createApp(App)
const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)

// Plugins Vue
app.use(pinia)
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

app.config.globalProperties.$store = {}

// Instância dos stores
const auth = useAuthStore()
const themeSettingsStore = useThemeSettingsStore()

// Aplica tema salvo (mesmo sem usuário logado)
setupThemeFromLocalStorage(themeSettingsStore)

// Sincronização de logout entre abas
window.addEventListener("storage", (e) => {
  if (e.key === "activeUser" && !e.newValue) {
    auth.user = null
    console.log("Logout detectado em outra aba.")
  }
})

// Inicializa app
async function initializeApp() {
  try {
    await api.get("/sanctum/csrf-cookie")

    const userOk = await auth.fetchUser()

    // Se sessão inválida, redireciona e monta o app
    if (!userOk) {
      await router.replace({ name: 'login' })
      return app.mount("#app")
    }

    // Se logado, carrega padarias
    const { useActiveBakeryStore } = await import('@/store/activeBakeryStore')
    const activeBakeryStore = useActiveBakeryStore()

    await activeBakeryStore.fetchMyBakeries()
    activeBakeryStore.loadActiveBakeryFromStorage()

  } catch (error) {
    console.error("Erro na inicialização:", error)
  }

  // Monta o app depois de tudo (inclusive login inválido)
  app.mount("#app")
}

initializeApp()
