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
import { VueQueryPlugin } from "@tanstack/vue-query";
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import { useThemeSettingsStore } from "@/store/themeSettings";
import { setupThemeFromLocalStorage } from '@/utils/theme'

// perfect scrollbar
import PerfectScrollbar from "vue3-perfect-scrollbar";
import "vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css";
import api from '@/plugins/axios'
import { useAuthStore } from '@/store/authStore'

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

const auth = useAuthStore();

// Escuta alterações no localStorage feitas em outras abas
window.addEventListener('storage', (e) => {
  if (e.key === 'activeUser' && !e.newValue) {
    // Usuário saiu em outra aba, limpar estado local também
    auth.user = null
    console.log('Logout detectado em outra aba, limpando usuário local');
  }
});

async function initializeApp() {
  try {
    // Passo 1: Requisição ao CSRF para garantir que o cookie será configurado
    await api.get('/sanctum/csrf-cookie'); // Essa chamada garante que o cookie CSRF seja configurado
    
    // Passo 2: Verifica se há um token no localStorage
    const token = localStorage.getItem('token');
    if (token) {
      // Passo 3: Se houver token, tenta buscar os dados do usuário
      await auth.fetchUser();
    }
  } catch (error) {
    console.error('Erro durante a inicialização do aplicativo:', error);
  } finally {
      // Passo 4: Monta o app apenas depois de garantir que o CSRF e o token estão configurados
    app.mount('#app');
  }
}

// Inicia o processo
initializeApp();

app.config.globalProperties.$store = {};

app.use(VueQueryPlugin);

const themeSettingsStore = useThemeSettingsStore();

setupThemeFromLocalStorage(themeSettingsStore);


