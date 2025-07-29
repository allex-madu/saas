<template>
  <div>
    <Breadcrumb />
    <div class="grid grid-cols-12 gap-5 mb-5">
      <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
        <div
          class="bg-no-repeat bg-cover bg-center p-5 rounded-[6px] relative"
          :style="{ backgroundImage: `url(${widgetbg2})` }"
        >
          <div>
            <h4 class="text-xl font-medium text-white mb-2">
              <span class="block font-normal">Good evening,</span>
              <span class="block">Mr. Dianne Russell</span>
            </h4>
            <p class="text-sm text-white font-normal">Welcome to Dashcode</p>
          </div>
        </div>
      </div>
      <div class="2xl:col-span-9 lg:col-span-8 col-span-12">
        <div class="grid md:grid-cols-3 grid-cols-1 gap-4">
          <div v-for="(item, i) in statistics" :key="i">
            <Card bodyClass="pt-4 pb-3 px-4">
              <div class="flex space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                  <div
                    class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl"
                    :class="`${item.bg} ${item.text}`"
                  >
                    <Icon :icon="item.icon" />
                  </div>
                </div>
                <div class="flex-1">
                  <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                    {{ item.title }}
                  </div>
                  <div class="text-slate-900 dark:text-white text-lg font-medium">
                    {{ item.count }}
                  </div>
                </div>
              </div>
              <div class="ltr:ml-auto rtl:mr-auto max-w-[124px]">
                <apexchart
                  type="area"
                  height="41"
                  width="124"
                  :options="item.name.chartOptions"
                  :series="item.name.series"
                />
              </div>
            </Card>
          </div>
        </div>
      </div>
    </div>
    <div class="grid grid-cols-12 gap-5">
      <div class="2xl:col-span-8 lg:col-span-7 col-span-12">
        <Card>
          <div class="legend-ring">
            <apexchart
              type="bar"
              height="420"
              :options="columnCharthomeComputed.chartOptions"
              :series="columnCharthomeComputed.series"
            />
          </div>
        </Card>
      </div>
      <div class="2xl:col-span-4 lg:col-span-5 col-span-12">
        <Card title="Statistic">
          <template #header>
            <DropEvent />
          </template>
          <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
            <div class="bg-slate-50 dark:bg-slate-900 rounded pt-3 px-4">
              <div class="text-sm text-slate-600 dark:text-slate-300 mb-[6px]">Orders</div>
              <div class="text-lg text-slate-900 dark:text-white font-medium mb-[6px]">123k</div>
              <div class="font-normal text-xs text-slate-600 dark:text-slate-300">
                <span class="text-warning-500">-60%</span> From last Week
              </div>
              <div class="mt-4">
                <apexchart
                  type="bar"
                  height="44"
                  :options="columnCharthome2.chartOptions"
                  :series="columnCharthome2.series"
                />
              </div>
            </div>
            <div class="bg-slate-50 dark:bg-slate-900 rounded pt-3 px-4">
              <div class="text-sm text-slate-600 dark:text-slate-300 mb-[6px]">Profit</div>
              <div class="text-lg text-slate-900 dark:text-white font-medium mb-[6px]">654k</div>
              <div class="font-normal text-xs text-slate-600 dark:text-slate-300">
                <span class="text-primary-500">+02%</span> From last Week
              </div>
              <div class="mt-4">
                <apexchart
                  type="line"
                  height="44"
                  :options="isDark ? areaLineDark.chartOptions : areaLine.chartOptions"
                  :series="areaLine.series"
                />
              </div>
            </div>
            <div class="md:col-span-2 bg-slate-50 dark:bg-slate-900 rounded py-3 px-4">
              <div class="flex items-center">
                <div class="flex-none">
                  <div class="text-sm text-slate-600 dark:text-slate-300 mb-[6px]">Earnings</div>
                  <div class="text-lg text-slate-900 dark:text-white font-medium mb-[6px]">
                    $12,335.00
                  </div>
                  <div class="font-normal text-xs text-slate-600 dark:text-slate-300">
                    <span class="text-primary-500">+08%</span> From last Week
                  </div>
                </div>
                <div class="flex-1">
                  <div class="legend-ring2">
                    <apexchart
                      type="donut"
                      height="200"
                      :options="donutChartDarkComputed.chartOptions"
                      :series="donutChartDarkComputed.series"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Card>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, reactive, computed } from 'vue'
import { useThemeSettingsStore } from '@/store/themeSettings'

// Importando componentes e assets
import Card from '@/components/Card'
import Icon from '@/components/Icon'
import Customer from './Analytics-Component/Customer'
import DropEvent from './Analytics-Component/DropEvent'
import Etable from './Analytics-Component/Etable'
import Map from './Analytics-Component/Map2'
import Products from './Analytics-Component/Products'
import SelectMonth from './Analytics-Component/SelectMonth'
import Breadcrumb from './Analytics-Component/Breadcrumbs'
import widgetbg2 from '@/assets/images/all-img/widget-bg-2.png'

// Importando dados
import {
  areaLine,
  areaLineDark,
  basicArea,
  basicAreaDark,
  columnCharthome,
  columnCharthomeDark,
  columnCharthome2,
  donutChart,
  donutChartDark,
  radarChart,
  radarChartDark,
  shapeLine1,
  shapeLine2,
  shapeLine3,
  mostSales2,
} from './Analytics-Component/data'

const themeSettingsStore = useThemeSettingsStore()
const isDark = computed(() => themeSettingsStore.isDark)
const direction = computed(() => themeSettingsStore.direction)

const fillterMap = ref('usa')

const statistics = [
  {
    name: shapeLine1,
    title: 'Totel revenue',
    count: '3,564',
    bg: 'bg-[#E5F9FF] dark:bg-slate-900',
    text: 'text-info-500',
    icon: 'heroicons:shopping-cart'
  },
  {
    name: shapeLine2,
    title: 'Products sold',
    count: '564',
    bg: 'bg-[#FFEDE6] dark:bg-slate-900',
    text: 'text-warning-500',
    icon: 'heroicons:cube'
  },
  {
    name: shapeLine3,
    title: 'Growth',
    count: '+5.0%',
    bg: 'bg-[#EAE6FF] dark:bg-slate-900',
    text: 'text-[#5743BE]',
    icon: 'heroicons:arrow-trending-up-solid'
  }
]

const columnCharthomeComputed = computed(() => {
  return {
    series: [
      { name: 'Net Profit', data: [44, 55, 57, 56, 61, 58, 63, 60, 66] },
      { name: 'Revenue', data: [76, 85, 101, 98, 87, 105, 91, 114, 94] },
      { name: 'Free Cash Flow', data: [35, 41, 36, 26, 45, 48, 52, 53, 41] }
    ],
    chartOptions: {
      chart: { toolbar: { show: false } },
      plotOptions: {
        bar: { horizontal: false, endingShape: 'rounded', columnWidth: '45%' }
      },
      legend: {
        show: true,
        position: 'top',
        horizontalAlign: 'right',
        fontSize: '12px',
        fontFamily: 'Inter',
        offsetY: -30,
        markers: {
          width: 8, height: 8, offsetY: -1, offsetX: -5, radius: 12
        },
        labels: { colors: isDark.value ? '#CBD5E1' : '#475569' },
        itemMargin: { horizontal: 18, vertical: 0 }
      },
      title: {
        text: 'Revenue Report',
        align: 'left',
        offsetX: direction.value ? '0%' : 0,
        offsetY: 13,
        floating: false,
        style: {
          fontSize: '20px', fontWeight: '500', fontFamily: 'Inter',
          color: isDark.value ? '#fff' : '#0f172a'
        }
      },
      dataLabels: { enabled: false },
      stroke: { show: true, width: 2, colors: ['transparent'] },
      yaxis: {
        opposite: direction.value ? true : false,
        labels: {
          style: {
            colors: isDark.value ? '#CBD5E1' : '#475569',
            fontFamily: 'Inter'
          }
        }
      },
      xaxis: {
        categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
        labels: {
          style: {
            colors: isDark.value ? '#CBD5E1' : '#475569',
            fontFamily: 'Inter'
          }
        },
        axisBorder: { show: false },
        axisTicks: { show: false }
      },
      fill: { opacity: 1 },
      tooltip: {
        y: { formatter: val => `$ ${val} thousands` }
      },
      colors: ['#4669FA', '#0CE7FA', '#FA916B'],
      grid: {
        show: true,
        borderColor: isDark.value ? '#334155' : '#E2E8F0',
        strokeDashArray: 10,
        position: 'back'
      },
      responsive: [
        {
          breakpoint: 600,
          options: {
            legend: {
              position: 'bottom', offsetY: 8, horizontalAlign: 'center'
            },
            plotOptions: { bar: { columnWidth: '80%' } }
          }
        }
      ]
    }
  }
})

const donutChartDarkComputed = computed(() => {
  return {
    series: [44, 55],
    chartOptions: {
      labels: ['success', 'Return'],
      dataLabels: { enabled: false },
      colors: ['#0CE7FA', '#FA916B'],
      legend: {
        position: 'bottom',
        fontSize: '14px',
        fontFamily: 'Inter',
        fontWeight: 400,
        markers: { width: 8, height: 8, offsetY: 0, offsetX: -5, radius: 12 },
        itemMargin: { horizontal: 18, vertical: 0 },
        labels: { colors: isDark.value ? '#CBD5E1' : '#475569' }
      },
      plotOptions: {
        pie: { donut: { size: '65%' } }
      },
      responsive: [
        {
          breakpoint: 480,
          options: { legend: { position: 'bottom' } }
        }
      ]
    }
  }
})
</script>
<style></style>
