<template>
  <div>
    <Breadcrumb />
    <div class="card-auto space-y-5">
      <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-8 col-span-12 space-y-5">
          <Card>
            <div class="grid grid-cols-12 gap-5">
              <div class="xl:col-span-8 col-span-12">
                <!-- statitces start -->
                <div
                  class="grid md:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-3"
                >
                  <div
                    v-for="(item, i) in statistics"
                    :key="i"
                    :class="item.bg"
                    class="rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-50 text-center"
                  >
                    <div
                      class="mx-auto h-10 w-10 flex flex-col items-center justify-center rounded-full bg-white text-2xl mb-4"
                      :class="item.text"
                    >
                      <Icon :icon="item.icon" />
                    </div>
                    <span
                      class="block text-sm text-slate-600 font-medium dark:text-white mb-1"
                      >{{ item.title }}</span
                    >
                    <span
                      class="block mb- text-2xl text-slate-900 dark:text-white font-medium"
                      >{{ item.count }}</span
                    >
                  </div>
                </div>
              </div>
              <!-- pie chart start -->
              <div class="xl:col-span-4 col-span-12">
                <div class="bg-slate-50 dark:bg-slate-900 rounded-md p-4">
                  <span class="block dark:text-slate-400 text-sm text-slate-600"
                    >Progress</span
                  >
                  <apexchart
                    type="donut"
                    height="113"
                    :options="isDark ? donutTwoDark.chartOptions : donutTwo.chartOptions"
                    :series="donutTwo.series"
                  />
                </div>
              </div>
            </div>
          </Card>
          <Card title="Deal distribution by stage">
            <template #header>
              <DropEvent />
            </template>
            <apexchart
              type="area"
              height="310"
              :options="isDark ? basicAreaDark.chartOptions : basicArea.chartOptions"
              :series="basicArea.series"
            />
          </Card>
        </div>
        <div class="lg:col-span-4 col-span-12 space-y-5">
          <Card title="Notes">
            <template #header>
              <button type="button" class="btn btn-dark btn-sm">
                Add More
              </button>
            </template>
            <div class="-mx-6 custom-calender">
              <v-calendar
                is-expanded
                :attributes="attributes"
                :is-dark="isDark"

              />
            </div>
            <ul class="divide-y divide-slate-100 dark:divide-slate-700">
              <li v-for="(item, i) in meets" :key="i" class="block py-[10px]">
                <div class="flex space-x-2 rtl:space-x-reverse">
                  <div class="flex-1 flex space-x-2 rtl:space-x-reverse">
                    <div class="flex-none">
                      <div class="h-8 w-8">
                        <img
                          :src="item.img"
                          alt=""
                          class="block w-full h-full object-cover rounded-full border hover:border-white border-transparent"
                        />
                      </div>
                    </div>
                    <div class="flex-1">
                      <span
                        class="block text-slate-600 text-sm dark:text-slate-300 mb-1 font-medium"
                        >{{ item.title }}</span
                      >
                      <span
                        class="flex font-normal text-xs dark:text-slate-400 text-slate-500"
                      >
                        <span class="text-base inline-block mr-1"
                          ><Icon icon="heroicons-outline:video-camera"
                        /></span>
                        {{ item.meet }}
                      </span>
                    </div>
                  </div>
                  <div class="flex-none">
                    <span
                      class="block text-xs text-slate-600 dark:text-slate-400"
                      >{{ item.date }}</span
                    >
                  </div>
                </div>
              </li>
            </ul>
          </Card>
        </div>
      </div>

      <div class="grid xl:grid-cols-3 grid-cols-1 gap-5">
        <!-- to do list -->
        <Card title="Task list">
          <template #header>
            <DropEvent />
          </template>
          <ul
            class="divide-y divide-slate-100 dark:divide-slate-700 -mx-6 -mb-6"
          >
            <li
              class="flex items-center px-6 space-x-4 py-4 rtl:space-x-reverse"
              v-for="item in TodoList"
              :key="item.id"
            >
              <div
                class="flex-none flex space-x-2 items-center rtl:space-x-reverse"
              >
                <Checkbox v-model="item.isDone" />
                <div
                  class="h-8 w-8 rounded-full text-white"
                  :class="item.isDone ? ' opacity-20' : ' opacity-100'"
                >
                  <img
                    :src="item.image"
                    alt=""
                    class="block w-full h-full object-cover rounded-full"
                  />
                </div>
              </div>
              <div
                class="flex-1 flex"
                :class="item.isDone ? 'line-through dark:text-white' : ''"
              >
                <span class="flex-1 text-sm text-slate-600 dark:text-slate-300">
                  {{ item.title.slice(0, 20) + "..." }}</span
                >
                <span
                  class="flex-none space-x-2 text-base text-secondary-500 rtl:space-x-reverse"
                >
                  <button type="button" v-if="item.isDone === false">
                    <Icon icon="heroicons-outline:pencil-alt" />
                  </button>
                  <button
                    type="button"
                    @click="removeTodo(item.id)"
                    class="transition duration-150 hover:text-danger-500"
                  >
                    <Icon icon="heroicons-outline:trash" />
                  </button>
                </span>
              </div>
            </li>
          </ul>
        </Card>
        <!-- message list -->
        <Card title="Messages">
          <template #header>
            <DropEvent />
          </template>
          <ul
            class="divide-y divide-slate-100 dark:divide-slate-700 -mx-6 -mb-6"
          >
            <li v-for="(item, i) in message.slice(0, 5)" :key="i">
              <router-link
                to="chat"
                class="hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-slate-800 text-slate-600 dark:text-slate-300 block w-full px-4 py-3 text-sm mb-2 last:mb-0 cursor-pointer"
              >
                <div class="flex ltr:text-left rtl:text-right">
                  <div class="flex-none ltr:mr-3 rtl:ml-3">
                    <div
                      class="h-8 w-8 bg-white dark:bg-slate-700 rounded-full relative"
                    >
                      <span
                        :class="`${
                          item.active ? 'bg-secondary-500' : 'bg-success-500'
                        } w-[10px] h-[10px] rounded-full border border-white dark:border-slate-700  inline-block absolute right-0 top-0`"
                      ></span>
                      <img
                        :src="item.image"
                        alt=""
                        class="block w-full h-full object-cover rounded-full border hover:border-white border-transparent"
                      />
                    </div>
                  </div>
                  <div class="flex-1">
                    <div
                      class="text-slate-800 dark:text-slate-300 text-sm font-medium mb-1`"
                    >
                      {{ item.title }}
                    </div>
                    <div
                      class="text-xs hover:text-[#68768A] font-normal text-slate-600 dark:text-slate-300"
                    >
                      {{ item.desc }}
                    </div>
                    <div
                      class="text-slate-400 dark:text-slate-400 text-xs mt-1"
                    >
                      3 min ago
                    </div>
                  </div>
                  <div class="flex-0" v-if="item.hasnotifaction">
                    <span
                      class="h-4 w-4 bg-danger-500 border border-white rounded-full text-[10px] flex items-center justify-center text-white"
                    >
                      {{ item.notification_count }}
                    </span>
                  </div>
                </div>
              </router-link>
            </li>
          </ul>
        </Card>
        <!-- active style two -->
        <Card title="Activity">
          <template #header>
            <DropEvent />
          </template>
          <ul class="relative ltr:pl-2 rtl:pr-2">
            <li
              v-for="(item, i) in trackingParcel.slice(0, 5)"
              :key="i"
              :class="
                item.status === 'ok'
                  ? 'before:opacity-100'
                  : ' before:opacity-50'
              "
              class="ltr:border-l-2 rtl:border-r-2 border-slate-100 dark:border-slate-700 pb-4 last:border-none ltr:pl-[22px] rtl:pr-[22px] relative before:absolute ltr:before:left-[-8px] rtl:before:-right-2 before:top-[0px] before:rounded-full before:w-4 before:h-4 before:bg-slate-900 dark:before:bg-slate-600 before:leading-[2px] before:content-[url('@/assets/images/all-img/ck.svg')]"
            >
              <div class="p-[10px] relative top-[-20px]">
                <h2
                  class="text-sm font-medium dark:text-slate-400-900 mb-1 text-slate-600"
                >
                  {{ item.title }}
                </h2>
                <p class="text-xs capitalize dark:text-slate-400">
                  {{ item.date }}
                </p>
              </div>
            </li>
          </ul>
        </Card>
      </div>
      <div class="grid grid-cols-12 gap-5">
        <div class="xl:col-span-8 lg:col-span-7 col-span-12">
          <Card title="Team members" noborder>
            <template #header>
              <SelectMonth />
            </template>
            <Teamtable
          /></Card>
        </div>
        <div class="xl:col-span-4 lg:col-span-5 col-span-12">
          <Card title="Files">
            <template #header>
              <DropEvent />
            </template>
            <ul class="divide-y divide-slate-100 dark:divide-slate-700">
              <li v-for="(item, i) in files" :key="i" class="block py-[8px]">
                <div class="flex space-x-2 rtl:space-x-reverse">
                  <div class="flex-1 flex space-x-2 rtl:space-x-reverse">
                    <div class="flex-none">
                      <div class="h-8 w-8">
                        <img
                          :src="item.img"
                          alt=""
                          class="block w-full h-full object-cover rounded-full border hover:border-white border-transparent"
                        />
                      </div>
                    </div>
                    <div class="flex-1">
                      <span
                        class="block text-slate-600 text-sm dark:text-slate-300"
                        >{{ item.title }}</span
                      >
                      <span
                        class="block font-normal text-xs text-slate-500 mt-1"
                        >{{ item.date }}</span
                      >
                    </div>
                  </div>
                  <div class="flex-none">
                    <button
                      type="button"
                      class="text-xs text-slate-900 dark:text-white"
                    >
                      Download
                    </button>
                  </div>
                </div>
              </li>
            </ul>
          </Card>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useThemeSettingsStore } from '@/store/themeSettings'

import Breadcrumb from './Analytics-Component/Breadcrumbs'
import Card from '@/components/Card'
import Checkbox from '@/components/Checkbox'
import Icon from '@/components/Icon'
import DropEvent from './Analytics-Component/DropEvent'
import SelectMonth from './Analytics-Component/SelectMonth'
import Teamtable from './Analytics-Component/Teamtable'

import { message as messageData, TodoList as todoData, trackingParcel as trackingData } from '@/constant/data'
import { basicArea, basicAreaDark, donutTwo, donutTwoDark, pieChart } from './Analytics-Component/data'

import sk from '@/assets/images/svg/sk.svg'
import path from '@/assets/images/svg/path.svg'
import dc from '@/assets/images/svg/dc.svg'

import file1Img from '@/assets/images/icon/file-1.svg'
import file2Img from '@/assets/images/icon/pdf-1.svg'
import file3Img from '@/assets/images/icon/zip-1.svg'
import file4Img from '@/assets/images/icon/pdf-2.svg'
import file5Img from '@/assets/images/icon/scr-1.svg'

const themeSettingsStore = useThemeSettingsStore()
const isDark = computed(() => themeSettingsStore.isDark)

const statistics = ref([
  { title: 'Total Task', count: '64', bg: 'bg-info-500', text: 'text-info-500', percent: '25.67%', icon: 'heroicons-outline:menu-alt-1' },
  { title: 'Completed', count: '45', bg: 'bg-warning-500', text: 'text-warning-500', percent: '8.67%', icon: 'heroicons-outline:chart-pie' },
  { title: 'Hours', count: '190', bg: 'bg-primary-500', text: 'text-primary-500', percent: '1.67%', icon: 'heroicons-outline:clock' },
  { title: 'Spendings', count: '$3,564', bg: 'bg-success-500', text: 'text-success-500', percent: '11.67%', icon: 'heroicons-outline:calculator' }
])

const attributes = ref([
  { key: 'today', highlight: { color: 'gray-500', fillMode: 'solid' }, dates: new Date() },
  {}
])

const meets = ref([
  { img: sk, title: 'Meeting with client', date: '01 Nov 2021', meet: 'Zoom meeting' },
  { img: path, title: 'Design meeting (team)', date: '01 Nov 2021', meet: 'Skyp meeting' },
  { img: dc, title: 'Background research', date: '01 Nov 2021', meet: 'Google meeting' },
  { img: sk, title: 'Meeting with client', date: '01 Nov 2021', meet: 'Zoom meeting' }
])

const files = ref([
  { img: file1Img, title: 'Dashboard.fig', date: '06 June 2021 / 155MB' },
  { img: file2Img, title: 'Ecommerce.pdf', date: '06 June 2021 / 155MB' },
  { img: file3Img, title: 'Job portal_app.zip', date: '06 June 2021 / 155MB' },
  { img: file4Img, title: 'Ecommerce.pdf', date: '06 June 2021 / 155MB' },
  { img: file5Img, title: 'Screenshot.jpg', date: '06 June 2021 / 155MB' }
])

const TodoList = ref([...todoData])
const message = ref([...messageData])
const trackingParcel = ref([...trackingData])

function removeTodo(id) {
  TodoList.value = TodoList.value.filter(todo => todo.id !== id)
}
</script>

<style lang="scss">
/* permanece igual */
</style>

<style lang="scss">
.custom-calender {
  .vc-pane-container,
  .vc-pane-layout,
  .vc-container,
  .vc-container * {
    border: none !important;
    font-family: "Inter", sans-serif !important;
    //background: #fafbff !important;
    font-weight: 400;
  }
  .vc-title {
    color: #0f172a !important;
    font-size: 24px !important;
    font-weight: 500 !important;
    margin-bottom: 15px !important;
  }
  .vc-arrow {
    margin-left: 10px !important;
    margin-right: 10px !important;
    color: #0f172a !important;
  }
  .vc-weekday {
    @apply font-normal dark:text-slate-400 text-sm text-slate-600;
  }
  .vc-day {
    @apply text-slate-900 dark:text-white;
  }
  .vc-highlight {
    background-color: #0f172a !important;
  }
}
.dark {
  .custom-calender {
    .vc-title {
      color: #fff !important;
    }
    .vc-arrow {
      color: #fff !important;
    }
  }
  .vc-container,
  .vc-container {
    @apply bg-slate-800;
  }
}
</style>
