<template>
  <Dropdown classMenuItems=" w-[180px] top-[58px] ">
    <div class="flex items-center">
      <div class="flex-1 ltr:mr-[10px] rtl:ml-[10px]">
        <div class="lg:h-8 lg:w-8 h-7 w-7 rounded-full">
          <img
            :src= "profileImg"
            alt=""
            class="block w-full h-full object-cover rounded-full"
          />
        </div>
      </div>
      <div
        class="flex-none text-slate-600 dark:text-white text-sm font-normal items-center lg:flex hidden overflow-hidden text-ellipsis whitespace-nowrap"
      >
        <span
          class="overflow-hidden text-ellipsis whitespace-nowrap w-[85px] block"
          >{{ auth.user?.person?.name || 'Usuário' }}</span>
        
        <span class="text-base inline-block ltr:ml-[10px] rtl:mr-[10px]"
          ><Icon icon="heroicons-outline:chevron-down"></Icon
        ></span>
      </div>
    </div>
    <template #menus>
      <MenuItem v-slot="{ active }" v-for="(item, i) in ProfileMenu" :key="i">
        <div
          type="button"
          :class="`${
            active
              ? 'bg-slate-100 dark:bg-slate-700 dark:bg-opacity-70 text-slate-900 dark:text-slate-300'
              : 'text-slate-600 dark:text-slate-300'
          } `"
          class="inline-flex items-center space-x-2 rtl:space-x-reverse w-full px-4 py-2 first:rounded-t last:rounded-b font-normal cursor-pointer"
          @click="item.link()"
        >
          <div class="flex-none text-lg">
            <Icon :icon="item.icon" />
          </div>
          <div class="flex-1 text-sm">
            {{ item.label }}
          </div>
        </div>
      </MenuItem>
    </template>
  </Dropdown>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { MenuItem } from '@headlessui/vue'
import Dropdown from '@/components/Dropdown'
import Icon from '@/components/Icon'
import profileImg from '@/assets/images/all-img/user.png'
import { useAuthStore } from '@/store/authStore'
import { useToast } from 'vue-toastification'

const toast = useToast()

const router = useRouter()

const auth = useAuthStore()

const handleLogout = async () => 
{
  const userName = auth.user?.person?.name ?? 'Usuário'
  await auth.logout()
  toast.success(`Usuário ${userName} deslogado com sucesso`, {
    timeout: 2000
  })
  router.push('/') // ou onde desejar redirecionar
}


const ProfileMenu = [
  {
    label: 'Profile',
    icon: 'heroicons-outline:user',
    link: () => router.push('profile'),
  },
  {
    label: 'Chat',
    icon: 'heroicons-outline:chat',
    link: () => router.push('chat'),
  },
  {
    label: 'Email',
    icon: 'heroicons-outline:mail',
    link: () => router.push('email'),
  },
  {
    label: 'Todo',
    icon: 'heroicons-outline:clipboard-check',
    link: () => router.push('todo'),
  },
  {
    label: 'Settings',
    icon: 'heroicons-outline:cog',
    link: () => router.push('settings'),
  },
  {
    label: 'Price',
    icon: 'heroicons-outline:credit-card',
    link: () => router.push('pricing'),
  },
  {
    label: 'Faq',
    icon: 'heroicons-outline:information-circle',
    link: () => router.push('faq'),
  },
  {
    
    label: 'Logout',
    icon: 'heroicons-outline:login',
    link: handleLogout,
  },
]



</script>



<style lang=""></style>
