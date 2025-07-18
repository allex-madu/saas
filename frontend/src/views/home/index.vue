<template>
  <div>
    



 <div v-if="auth.hasRole(['admin', 'super-admin'])">

  <RouterLink :to="{ name: 'admin.users' }" class="text-blue-600 hover:underline">
    Gerenciar Usuários
  </RouterLink>
  
  <br>

  


</div>



    <div
      v-if="auth.user?.roles.includes('super-admin') || auth.user?.roles.includes('admin')"
      class="grid grid-cols-12 gap-5 mb-5"
    >
      <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
        <div
          class="bg-no-repeat bg-cover bg-center p-4 rounded-[6px] relative"
          :style="{ backgroundImage: 'url(' + widget1 + ')' }"
        >
          <div class="max-w-[169px]">
            <div class="text-xl font-medium text-slate-900 mb-2">
              Upgrade your Dashcode
            </div>
            <p class="text-sm text-slate-800">Pro plan for better results</p>
          </div>
          <div
            class="absolute top-1/2 -translate-y-1/2 ltr:right-6 rtl:left-6 mt-2 h-12 w-12 bg-white text-slate-900 rounded-full text-xs font-medium flex flex-col items-center justify-center"
          >
            Now
          </div>
        </div>
      </div>
      <div class="2xl:col-span-9 lg:col-span-8 col-span-12">
        <Card bodyClass="p-4">
          <div class="grid md:grid-cols-3 col-span-1 gap-4">
            <div
              class="py-[18px] px-4 rounded-[6px]"
              v-for="(item, i) in statistics"
              :class="item.bg"
              :key="i"
            >
              <div class="flex items-center space-x-6 rtl:space-x-reverse">
                <div class="flex-none">
                  <apexchart
                    type="area"
                    height="48"
                    width="48"
                    :options="item.name.chartOptions"
                    :series="item.name.series"
                  />
                </div>
                <div class="flex-1">
                  <div class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                    {{ item.title }}
                  </div>
                  <div class="text-slate-900 dark:text-white text-lg font-medium">
                    {{ item.count }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Card>
      </div>
    </div>



    <div class="grid grid-cols-12 gap-5">
      <div class="lg:col-span-8 col-span-12">
        <Card>
          <div class="legend-ring">
            <apexchart
              type="bar"
              height="400"
              :options="columnCharthomeComputed.chartOptions"
              :series="columnCharthomeComputed.series"
            />
          </div>
        </Card>
      </div>

      <div class="lg:col-span-4 col-span-12">
        <Card title="overview">
          <template #header>
            <DropEvent />
          </template>
          <apexchart
            type="radialBar"
            :height="windowWidth > 768 ? 350 : 250"
            :options="isDark.value ? MultipleRadialbarsDark.chartOptions : MultipleRadialbars.chartOptions"
            :series="MultipleRadialbars.series"
          />
        </Card>
      </div>

      <!-- outros cards omitidos para brevidade -->
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/store/authStore'
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'
import { useThemeSettingsStore } from '@/store/themeSettings.js'
import Card from "@/components/Card"
import DropEvent from "./Analytics-Component/DropEvent"
import {
  gearradil,
  gearradilDark,
  MultipleRadialbars,
  MultipleRadialbarsDark,
} from "../../constant/appex-chart"
import { columnCharthome, shapeLine1, shapeLine2, shapeLine3 } from "./Analytics-Component/data"

import widget1 from "@/assets/images/all-img/widget-bg-1.png"

// Pinia store
const themeSettingsStore = useThemeSettingsStore()
const isDark = computed(() => themeSettingsStore.isDark)
const direction = computed(() => themeSettingsStore.direction)

// Responsividade
const windowWidth = ref(window.innerWidth)
const handleResize = () => {
  windowWidth.value = window.innerWidth
}
onMounted(() => {
  window.addEventListener('resize', handleResize)
})
onBeforeUnmount(() => {
  window.removeEventListener('resize', handleResize)
})

// Gráficos pequenos
const statistics = [
  { name: shapeLine1, title: "Total revenue", count: "3,564", bg: "bg-[#E5F9FF] dark:bg-slate-900" },
  { name: shapeLine2, title: "Products sold", count: "564", bg: "bg-[#FFEDE5] dark:bg-slate-900" },
  { name: shapeLine3, title: "Growth", count: "+5.0%", bg: "bg-[#EAE5FF] dark:bg-slate-900" },
]

// Gráfico de barras adaptado ao tema
const columnCharthomeComputed = computed(() => {
  return {
    series: columnCharthome.series,
    chartOptions: {
      ...columnCharthome.chartOptions,
      legend: {
        ...columnCharthome.chartOptions.legend,
        labels: { colors: isDark.value ? "#CBD5E1" : "#475569" },
      },
      title: {
        ...columnCharthome.chartOptions.title,
        style: {
          ...columnCharthome.chartOptions.title.style,
          color: isDark.value ? "#fff" : "#0f172a",
        },
        offsetX: direction.value ? "0%" : 0,
      },
      yaxis: {
        ...columnCharthome.chartOptions.yaxis,
        opposite: direction.value,
        labels: {
          style: {
            colors: isDark.value ? "#CBD5E1" : "#475569",
            fontFamily: "Inter",
          },
        },
      },
      xaxis: {
        ...columnCharthome.chartOptions.xaxis,
        labels: {
          style: {
            colors: isDark.value ? "#CBD5E1" : "#475569",
            fontFamily: "Inter",
          },
        },
      },
      grid: {
        ...columnCharthome.chartOptions.grid,
        borderColor: isDark.value ? "#334155" : "#E2E8F0",
      },
    },
  }
})


const auth = useAuthStore()




const handleLogout = async () => {
  
  await auth.logout()
  
  // Redirecionar para login, por exemplo
  
  
}
</script>

<style scoped></style>
