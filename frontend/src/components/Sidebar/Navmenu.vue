<template>
  <ul>
    <!-- Laço principal: percorre todos os itens de menu visíveis (filtrados por role, no script) -->
    <li
      v-for="(item, i) in visibleItems"
      :key="i"
      :class="[
        item.child ? 'item-has-children' : '',            // Aplica classe se o item tiver filhos
        activeSubmenu === i ? 'open' : '',               // Marca como submenu aberto se índice ativo
        route.name === item.link ? 'menu-item-active' : '' // Destaca item ativo com base no nome da rota
      ]"
      class="single-sidebar-menu"
    >

      <!-- Caso seja um item simples (sem filhos e não seja cabeçalho), renderiza como <router-link> -->
      <router-link
        v-if="!item.child && !item.isHeadr"
        :to="item.link"
        class="menu-link"
      >
        <!-- Ícone, se definido -->
        <span class="menu-icon flex-grow-0" v-if="item.icon">
          <Icon :icon="item.icon" />
        </span>

        <!-- Título do item -->
        <div class="text-box flex-grow" v-if="item.title">{{ item.title }}</div>

        <!-- Badge (só aparece se menu não estiver colapsado) -->
        <span
          class="menu-badge"
          v-if="item.badge && !themeSettingsStore.sidebarCollasp"
        >
          {{ item.badge }}
        </span>
      </router-link>

      <!-- Se for apenas um cabeçalho (sem filhos), exibe como texto simples -->
      <div v-else-if="item.isHeadr && !item.child" class="menulabel">
        {{ item.title }}
      </div>

      <!-- Se o item tiver filhos (submenu), renderiza estrutura interativa -->
      <div
        v-else
        class="menu-link"
        :class="[
          activeSubmenu === i ? 'parent_active not-collapsed' : 'collapsed',
          item.isHeadr ? 'pointer-events-none cursor-default' : ''
        ]"
        @click="!item.isHeadr && toggleSubmenu(i)"
      >

        <div class="flex-1 flex items-start">
          <!-- Ícone do item pai -->
          <span class="menu-icon" v-if="item.icon">
            <Icon :icon="item.icon" />
          </span>

          <!-- Título do item pai -->
          <div class="text-box" v-if="item.title">{{ item.title }}</div>
        </div>

        <!-- Ícone de seta com rotação dinâmica (indica aberto ou fechado) -->
        <div class="flex-0" v-if="!item.isHeadr">
          <div
            class="menu-arrow transform transition-all duration-300"
            :class="activeSubmenu === i
              ? 'ltr:rotate-90 rtl:rotate-90'
              : 'rtl:rotate-180'"
          >
            <Icon icon="heroicons-outline:chevron-right" />
          </div>
        </div>
      </div>

      <!-- Renderização animada do submenu, se estiver ativo -->
      <Transition
        enter-active-class="submenu_enter-active"
        leave-active-class="submenu_leave-active"
        @before-enter="beforeEnter"
        @enter="enter"
        @after-enter="afterEnter"
        @before-leave="beforeLeave"
        @leave="leave"
        @after-leave="afterLeave"
      >
        <!-- Lista de subitens (somente se o submenu estiver ativo) -->
        <ul class="sub-menu" v-if="activeSubmenu === i">
          <li
            v-for="(ci, index) in item.child"
            :key="index"
            class="block ltr:pl-4 rtl:pr-4 ltr:pr-1 rtl:-l-1 mb-4 first:mt-4"
          >
            <!-- Cada subitem também é um router-link -->
            <router-link :to="ci.childlink" v-slot="{ isActive }">
              <span
                class="text-sm flex space-x-3 rtl:space-x-reverse items-center transition-all duration-150"
                :class="isActive
                  ? 'text-slate-900 dark:text-white font-medium'
                  : 'text-slate-600 dark:text-slate-300'"
              >
                <!-- Bolinha que marca se está ativo -->
                <span
                  class="h-2 w-2 rounded-full border border-slate-600 dark:border-slate-300 inline-block flex-none"
                  :class="isActive
                    ? 'bg-slate-900 dark:bg-slate-300 ring-4 ring-opacity-[15%] ring-black-500 dark:ring-slate-300 dark:ring-opacity-20'
                    : ''"
                ></span>

                <!-- Título do subitem -->
                <span class="flex-1">{{ ci.childtitle }}</span>
              </span>
            </router-link>
          </li>
        </ul>
      </Transition>
    </li>

    <!-- Link fixo para a documentação do Dashcode -->
    <li class="single-sidebar-menu">
      <a
        href="https://dashcode-doc.codeshaper.tech/"
        target="_blank"
        class="menu-link"
      >
        <span class="menu-icon">
          <Icon icon="heroicons:document" />
        </span>
        <div class="text-box">Documentation</div>
      </a>
    </li>
  </ul>
</template>



<script setup>
import { ref, computed, watch, onMounted, nextTick } from 'vue'
import { useRoute } from 'vue-router'
import { useThemeSettingsStore } from '@/store/themeSettings'
import { useAuthStore } from '@/store/authStore'
import Icon from '../Icon'

// Recebe as propriedades do componente, como título, ícone e lista de itens do menu
const props = defineProps({
  title: { type: String, default: '' },
  icon: { type: String, default: '' },
  link: { type: String, default: '' },
  items: { type: Array, required: true },
  childrenLinks: { type: Array, default: null },
})

// Acesso às stores e rota atual
const themeSettingsStore = useThemeSettingsStore()
const authStore = useAuthStore()
const route = useRoute()

// Controla qual submenu está aberto
const activeSubmenu = ref(null)

// Alterna o submenu clicado
const toggleSubmenu = (index) => {
  activeSubmenu.value = activeSubmenu.value === index ? null : index
}

// Animações para entrada/saída de submenus (altura)
const beforeEnter = (el) => {
  requestAnimationFrame(() => {
    if (!el.style.height) el.style.height = '0px'
    el.style.display = null
  })
}
const enter = (el) => {
  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      el.style.height = `${el.scrollHeight}px`
    })
  })
}
const afterEnter = (el) => {
  el.style.height = null
}
const beforeLeave = (el) => {
  requestAnimationFrame(() => {
    if (!el.style.height) el.style.height = `${el.offsetHeight}px`
  })
}
const leave = (el) => {
  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      el.style.height = '0px'
    })
  })
}
const afterLeave = (el) => {
  el.style.height = null
}

// Lista filtrada de itens visíveis conforme papéis do usuário
const visibleItems = computed(() => {
  const user = authStore.user
  if (!user) return []

  const userRoles = user.roles?.map(role => role.name) || []

  // Função auxiliar para verificar se o usuário tem permissão para um menu
  const hasRole = (allowedRoles) => {
    if (!allowedRoles || allowedRoles.length === 0) return true
    return userRoles.some(role => allowedRoles.includes(role))
  }

  return props.items
    // Filtra menus com base nos papéis
    .filter(menu => hasRole(menu.role))
    // Filtra os submenus também, herdando papel do pai se necessário
    .map(menu => {
      const parentRole = menu.role
      return {
        ...menu,
        child: menu.child?.filter(child => hasRole(child.role ?? parentRole)) || []
      }
    })
    // Mantém cabeçalhos (isHeadr) e menus com filhos visíveis
  .filter(menu =>
      menu.isHeadr ||
      !menu.child ||
      (Array.isArray(menu.child) && menu.child.length > 0)
    )
  })

// Fecha o sidebar mobile ao navegar para nova rota
watch(() => route.name, () => {
  if (themeSettingsStore.mobielSidebar) {
    themeSettingsStore.mobielSidebar = false
  }

  // Fecha submenu se rota atual for de menu sem filhos
  props.items.forEach((item) => {
    if (item.link === route.name) {
      activeSubmenu.value = null
    }
  })
})

// Abre automaticamente o submenu que contém a rota ativa
onMounted(() => {
  nextTick(() => {
    const routeName = route.name
    visibleItems.value.forEach((item, i) => {
      const isActive = item.child?.some(child => {
        const childName = typeof child.childlink === 'string'
          ? child.childlink
          : child.childlink?.name
        return childName === routeName
      })
      if (isActive) activeSubmenu.value = i
    })
  })
})
</script>



<style lang="scss">
/* Animação suave para transição de abertura e fechamento dos submenus */
.submenu_enter-active,
.submenu_leave-active {
  overflow: hidden;
  transition: all 0.34s linear;
}

/* Ícone rotaciona suavemente quando submenu está aberto */
.not-collapsed .has-icon {
  transition: all 0.34s linear;
}
.not-collapsed .has-icon {
  @apply transform rotate-180;
}

/* Estilos gerais para cada item do menu lateral */
.single-sidebar-menu {
  @apply relative;

  /* Texto de seção (ex: "Gerenciamento") */
  .menulabel {
    @apply text-slate-800 dark:text-slate-300 text-xs font-semibold uppercase mb-4 mt-4;
  }

  /* Link principal do menu */
  > .menu-link {
    @apply flex text-slate-600 font-medium dark:text-slate-300 text-sm capitalize px-[10px] py-3 rounded-[4px] cursor-pointer;
  }

  /* Ícone do menu */
  .menu-icon {
    @apply icon-box inline-flex items-center text-slate-600 dark:text-slate-300 text-lg ltr:mr-3 rtl:ml-3;
  }
}

/* Estilo para menu que possui filhos (submenu) */
.item-has-children {
  .menu-arrow {
    @apply h-5 w-5 text-base text-slate-300 bg-slate-100 dark:bg-[#334155] dark:text-slate-300 rounded-full flex justify-center items-center;
  }
}

/* Estilos quando a sidebar está fechada (modo icônico) */
.close_sidebar .menulabel {
  @apply hidden;
}

.close_sidebar:not(.sidebar-hovered) {
  .menu-arrow {
    @apply hidden;
  }

  .single-sidebar-menu {
    /* Mostra o nome do menu em tooltip ao passar o mouse */
    .text-box {
      @apply absolute left-full ml-5 w-[180px] top-0 px-4 py-3 bg-white shadow-dropdown rounded-[4px] dark:bg-slate-800 z-[999] invisible opacity-0 transition-all duration-150;
    }

    &:hover {
      .text-box {
        @apply visible opacity-100;
      }
    }
  }

  /* Submenu em modo fechado (hover lateral com flyout) */
  .item-has-children {
    .text-box {
      @apply hidden;
    }

    > ul {
      @apply ml-4 absolute left-full top-0 w-[230px] bg-white shadow-dropdown rounded-[4px] dark:bg-slate-800 z-[999] px-4 pt-3 transition-all duration-150 invisible opacity-0;
      display: block !important; // força visibilidade em hover
    }

    &:hover {
      > ul {
        @apply visible opacity-100;
      }
    }
  }
}

/* Estilo visual para badges de notificação nos menus */
.menu-badge {
  @apply py-1 px-2 text-xs capitalize font-semibold rounded-[.358rem] whitespace-nowrap align-baseline inline-flex bg-slate-900 text-slate-50 dark:bg-slate-700 dark:text-slate-300;
}

/* Estilo quando submenu está ativo (aberto) */
.item-has-children {
  .parent_active {
    @apply bg-secondary-500 bg-opacity-20;

    .icon-box,
    .menu-icon,
    .text-box {
      @apply text-slate-700 dark:text-slate-200;
    }

    .menu-arrow {
      @apply bg-secondary-500 text-slate-600 text-opacity-70 bg-opacity-30 dark:text-white;
    }
  }
}

/* Estilo para link principal ativo (rota atual) */
.menu-item-active {
  .menu-link {
    @apply bg-slate-800 dark:bg-slate-700;

    .icon-box,
    .menu-icon,
    .text-box {
      @apply text-white dark:text-slate-300;
    }
  }

  .menu-badge {
    @apply bg-slate-100 text-slate-900;
  }
}
</style>

