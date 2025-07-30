import auth from "@/middleware/auth";
import guest from "@/middleware/guest";

const routes = [
  {
    path: "/",
    name: "Login",
    component: () => import("@/views/auth/login/index.vue"),
  },
  {
    path: "/login2",
    name: "login2",
    component: () => import("@/views/auth/login/login2.vue"),
  },
  {
    path: "/login3",
    name: "login3",
    component: () => import("@/views/auth/login/login3.vue"),
  },
  {
    path: "/register",
    name: "reg",
    component: () => import("@/views/auth/register"),
  },
  {
    path: "/register2",
    name: "reg2",
    component: () => import("@/views/auth/register/register2"),
  },
  {
    path: "/register3",
    name: "reg3",
    component: () => import("@/views/auth/register/register3"),
  },
  {
    path: "/forgot-password",
    name: "forgot-password",
    component: () => import("@/views/auth/forgot-password.vue"),
  },
  {
    path: "/forgot-password2",
    name: "forgot-password2",
    component: () => import("@/views/auth/forgot-password2.vue"),
  },
  {
    path: "/forgot-password3",
    name: "forgot-password3",
    component: () => import("@/views/auth/forgot-password3.vue"),
  },
  {
    path: "/lock-screen",
    name: "lock-screen",
    component: () => import("@/views/auth/lock-screen.vue"),
  },
  {
    path: "/lock-screen2",
    name: "lock-screen2",
    component: () => import("@/views/auth/lock-screen2.vue"),
  },
  {
    path: "/lock-screen3",
    name: "lock-screen3",
    component: () => import("@/views/auth/lock-screen3.vue"),
  },
  {
    path: "/success-500",
    name: "success-500",
    component: () => import("@/views/auth/success.vue"),
  },
  {
    path: "/app",
    name: "Layout",
    redirect: "/app/home",
    component: () => import("@/Layout/index.vue"),
    meta: {
      middleware: [auth],
      requiresAuth: true,
    },
    children: [
      {
        path: "blank-page",
        name: "blank-page",
        component: () => import("@/views/blank-page.vue"),
      },
      {
        path: "notifications",
        name: "notifications",
        component: () => import("@/views/notifications.vue"),
        meta: {
          hide: true,
        },
      },
      {
        path: "home",
        name: "home",
        component: () => import("@/views/home/index.vue"),
        meta: {
          hide: true,
        },
      },
      {/* USERS */
        path: 'admin/users',
        name: 'admin.users',
        component: () => import('@/views/admin/users/Table.vue'),
        meta: {
          middleware: [auth],         
          role: ['admin'],
          title: 'Lista de Usuários',
        }
      },
      {
        path: 'admin/users/:id',
        name: 'admin.users.show',
        component: () => import('@/views/admin/users/Show.vue'),
        meta: {
          middleware: [auth],        
          role: ['admin'],
          title: 'Detalhes do Usuário',
        },
      },
      {
        path: '/admin/users/create',
        name: 'admin.users.create',
        component: () => import('@/views/admin/users/Form.vue'),
      },
      {
        path: '/admin/users/:id/edit',
        name: 'admin.users.edit',
        component: () => import('@/views/admin/users/Form.vue'),
      },

      
      
       {/* PERMISSIONS */
        path: 'admin/permissions',
        name: 'admin.permissions.index',
        component: () => import('@/views/admin/permissions/Table.vue'),
        meta: {
          middleware: [auth],         
          role: ['admin'],
          title: 'Lista de Permissões',
        }
      },
      {
        path: 'admin/permissions/:id',
        name: 'admin.permissions.show',
        component: () => import('@/views/admin/permissions/Show.vue'),
        meta: { requiresAuth: true, role: ['admin'] }
      },
      {
        path: 'admin/permissions/create',
        name: 'admin.permissions.create',
        component: () => import('@/views/admin/permissions/Form.vue'),
        meta: {
          requiresAuth: true,
          role: ['admin', 'super-admin']
        }
      },
      {
        path: 'permissions/:id/edit',
        name: 'admin.permissions.edit',
        component: () => import('@/views/admin/permissions/Form.vue'),
        meta: { requiresAuth: true, role: ['admin', 'super-admin'] },
      },
      

      {/*  ASSIGNMENTS ( atribuições ) */
        path: 'admin/roles',
        name: 'admin.roles.index',
        component: () => import('@/views/admin/roles/Table.vue'),
        meta: { requiresAuth: true, role: ['admin', 'super-admin'] },
      },
      {
        path: 'admin/roles/create',
        name: 'admin.roles.create',
        component: () => import('@/views/admin/roles/Form.vue'), // ou RoleForm.vue
        meta: {
          requiresAuth: true,
          role: ['admin', 'super-admin']
        }
      },
      {
        path: 'admin/roles/:id/edit',
        name: 'admin.roles.edit',
        component: () => import('@/views/admin/roles/Form.vue'),
        meta: { requiresAuth: true, role: ['admin', 'super-admin'] },
      },
      {
        path: 'admin/roles/:id',
        name: 'admin.roles.show',
        component: () => import('@/views/admin/roles/Show.vue'),
        meta: { requiresAuth: true, role: ['admin', 'super-admin'] },
      },



      { /* BAKERIES */
        path: 'admin/bakeries',
        name: 'admin.bakeries.index',
        component: () => import('@/views/admin/bakeries/Table.vue'),
        meta: {
          requiresAuth: true,
          role: ['super-admin'],
          title: 'Padarias',
        },
      },
      {
        path: 'admin/bakeries/create',
        name: 'admin.bakeries.create',
        component: () => import('@/views/admin/bakeries/Form.vue'),
        meta: {
          requiresAuth: true,
          role: ['super-admin'],
          title: 'Nova Padaria',
        },
      },
      {
        path: 'admin/bakeries/:id/edit',
        name: 'admin.bakeries.edit',
        component: () => import('@/views/admin/bakeries/Form.vue'),
        meta: {
          requiresAuth: true,
          role: ['super-admin'], // ou o que for necessário
        },
      },
      {
        path: 'admin/bakeries/:id',
        name: 'admin.bakeries.show',
        component: () => import('@/views/admin/bakeries/Show.vue'),
        meta: {
          requiresAuth: true,
          role: ['super-admin'],
          title: 'Detalhes da Padaria',
        },
      },







      {
        path: "ecommerce",
        name: "ecommerce",
        component: () => import("@/views/home/ecommerce.vue"),
        meta: {
          hide: true,
        },
      },
      {
        path: "banking",
        name: "banking",
        component: () => import("@/views/home/banking.vue"),
        meta: {
          hide: true,
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "crm",
        name: "crm",
        component: () => import("@/views/home/crm.vue"),
        meta: {
          hide: true,
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "project",
        name: "project",
        component: () => import("@/views/home/project.vue"),
        meta: {
          hide: true,
          middleware: [auth],
          requiresAuth: true,
        },
      },
      {
        path: "changelog",
        name: "changelog",
        component: () => import("@/views/changelog.vue"),
        meta: {
          role: ['admin', 'super-admin'] 
        },
      },

      // components
      {
        path: "button",
        name: "button",
        component: () => import("@/views/components/button/index.vue"),
        meta: {
          groupParent: "components",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "alert",
        name: "alert",
        component: () => import("@/views/components/alert/index.vue"),
        meta: {
          groupParent: "components",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "card",
        name: "card",
        component: () => import("@/views/components/card/index.vue"),
        meta: {
          groupParent: "components",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "carousel",
        name: "carousel",
        component: () => import("@/views/components/carousel.vue"),
        meta: {
          groupParent: "components",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "dropdown",
        name: "dropdown",
        component: () => import("@/views/components/dropdown/index.vue"),
        meta: {
          groupParent: "components",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "modal",
        name: "dodal",
        component: () => import("@/views/components/modal/index.vue"),
        meta: {
          groupParent: "components",
        },
      },
      {
        path: "tab-accordion",
        name: "tab-accordion",
        component: () => import("@/views/components/tab-accordion/index.vue"),
        meta: {
          groupParent: "components",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "badges",
        name: "badges",
        component: () => import("@/views/components/badges.vue"),
        meta: {
          groupParent: "components",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "tooltip-popover",
        name: "tooltip-popover",
        component: () => import("@/views/components/tooltip-popover.vue"),
        meta: {
          groupParent: "components",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "typography",
        name: "typography",
        component: () => import("@/views/components/typography.vue"),
        meta: {
          groupParent: "components",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "colors",
        name: "colors",
        component: () => import("@/views/components/colors.vue"),
        meta: {
          groupParent: "components",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "image",
        name: "image",
        component: () => import("@/views/components/image/index.vue"),
        meta: {
          groupParent: "components",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "video",
        name: "video",
        component: () => import("@/views/components/video.vue"),
        meta: {
          groupParent: "components",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "pagination",
        name: "pagination",
        component: () => import("@/views/components/pagination"),
        meta: {
          groupParent: "components",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "progress-bar",
        name: "progress-bar",
        component: () => import("@/views/components/progress-bar/index.vue"),
        meta: {
          groupParent: "components",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "placeholder",
        name: "placeholder",
        component: () => import("@/views/components/placeholder.vue"),
        meta: {
          groupParent: "placeholder",
          role: ['admin', 'super-admin'] 
        },
      },
      // widgets
      {
        path: "basic",
        name: "basic",
        component: () => import("@/views/widgets/basic.vue"),
        meta: {
          groupParent: "widgets",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "statistic",
        name: "statistic",
        component: () => import("@/views/widgets/statistic.vue"),
        meta: {
          groupParent: "widgets",
          role: ['admin', 'super-admin'] 
        },
      },

      // forms
      {
        path: "input",
        name: "input",
        component: () => import("@/views/forms/input"),
        meta: {
          groupParent: "forms",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "input-group",
        name: "input-group",
        component: () => import("@/views/forms/input-group"),
        meta: {
          groupParent: "forms",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "input-layout",
        name: "input-layout",
        component: () => import("@/views/forms/input-layout"),
        meta: {
          groupParent: "forms",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "form-validation",
        name: "form-validation",
        component: () => import("@/views/forms/form-validation"),
        meta: {
          groupParent: "forms",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "form-wizard",
        name: "form-wizard",
        component: () => import("@/views/forms/form-wizard"),
        meta: {
          groupParent: "forms",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "form-repeater",
        name: "form-repeater",
        component: () => import("@/views/forms/form-repeater"),
        meta: {
          groupParent: "forms",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "input-mask",
        name: "input-mask",
        component: () => import("@/views/forms/input-mask"),
        meta: {
          groupParent: "forms",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "file-input",
        name: "file-input",
        component: () => import("@/views/forms/file-input"),
        meta: {
          groupParent: "forms",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "checkbox",
        name: "checkbox",
        component: () => import("@/views/forms/checkbox.vue"),
        meta: {
          groupParent: "forms",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "radio-button",
        name: "radio-button",
        component: () => import("@/views/forms/radio-button.vue"),
        meta: {
          groupParent: "forms",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "textarea",
        name: "textarea",
        component: () => import("@/views/forms/textarea"),
        meta: {
          groupParent: "forms",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "switch",
        name: "switch",
        component: () => import("@/views/forms/switch"),
        meta: {
          groupParent: "forms",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "select",
        name: "select",
        component: () => import("@/views/forms/select"),
        meta: {
          groupParent: "forms",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "date-time-picker",
        name: "date-time-picker",
        component: () => import("@/views/forms/date-time-picker"),
        meta: {
          groupParent: "forms",
          role: ['admin', 'super-admin'] 
        },
      },
      // table view
      {
        path: "table-basic",
        name: "table-basic",
        component: () => import("@/views/table/basic"),
        meta: {
          groupParent: "Table",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "table-advanced",
        name: "table-advanced",
        component: () => import("@/views/table/advanced"),
        meta: {
          groupParent: "Table",
          role: ['admin', 'super-admin'] 
        },
      },
      // chart
      {
        path: "appex-chart",
        name: "appex-chart",
        component: () => import("@/views/chart/appex-chart"),
        meta: {
          groupParent: "charts",
          role: ['admin', 'super-admin'] 
        },
      },
      {
        path: "chartjs",
        name: "chartjs",
        component: () => import("@/views/chart/chartjs"),
        meta: {
          groupParent: "charts",
          role: ['admin', 'super-admin'] 
        },
      },
      // app
      {
        path: "calender",
        name: "calender",
        component: () => import("@/views/app/calendar"),
        meta: {
          role: ['admin', 'super-admin'] 
        },
        
      },
      {
        path: "todo",
        name: "todo",
        component: () => import("@/views/app/todo"),
        meta: {
          hide: true,
          appheight: true,
          role: ['admin', 'super-admin']
        },
      },
      {
        path: "kanban",
        name: "kanban",
        component: () => import("@/views/app/kanban"),
        meta: {
          hide: true,
          role: ['admin', 'super-admin']
        },
      },
      {
        path: "email",
        name: "email",
        component: () => import("@/views/app/email"),
        meta: {
          groupParent: "Project",
          hide: true,
          appheight: true,
          role: ['admin', 'super-admin']
        },
      },
      {
        path: "projects",
        name: "projects",
        component: () => import("@/views/app/projects"),
        meta: {
          hide: true,
          role: ['admin', 'super-admin']
        },
      },
      {
        path: "project-details",
        name: "project-details",
        component: () => import("@/views/app/projects/project-details.vue"),
        meta: {
          hide: true,
          role: ['admin', 'super-admin']
        },
      },
      {
        path: "products",
        name: "products",
        component: () => import("@/views/app/ecommerce/index.vue"),
        meta: {
          hide: true,
          role: ['admin', 'super-admin']
        },
      },
      {
        path: "products/:id",
        name: "product-details",
        component: () => import("@/views/app/ecommerce/product-details.vue"),
        meta: {
          hide: true,
          role: ['admin', 'super-admin']
        },
      },
      {
        path: "cart",
        name: "cart",
        component: () => import("@/views/app/ecommerce/cart.vue"),
        meta: {
          hide: true,
          role: ['admin', 'super-admin']
        },
      },
      {
        path: "wishlist",
        name: "wishlist",
        component: () => import("@/views/app/ecommerce/wishlist.vue"),
        meta: {
          hide: true,
          role: ['admin', 'super-admin']
        },
      },

      {
        path: "add-product",
        name: "add-product",
        component: () => import("@/views/app/ecommerce/add-product.vue"),
        meta: {
          hide: true,
          role: ['admin', 'super-admin']
        },
      },
      {
        path: "edit-product",
        name: "edit-product",
        component: () => import("@/views/app/ecommerce/edit-product.vue"),
        meta: {
          hide: true,
          role: ['admin', 'super-admin']
        },
      },

      {
        path: "chat",
        name: "chat",
        component: () => import("@/views/app/chat"),
        meta: {
          hide: true,
          appheight: true,
          role: ['admin', 'super-admin']
        },
      },
      // normal pages
      {
        path: "invoice",
        name: "invoice",
        component: () => import("@/views/utility/invoice"),
        meta: {
          groupParent: "Utility",
          role: ['admin', 'super-admin']
        },
      },
      {
        path: "invoice-preview",
        name: "invoice-preview",
        component: () => import("@/views/utility/invoice/invoice-preview"),
        meta: {
          hide: true,
          role: ['admin', 'super-admin']
        },
      },
      {
        path: "invoice-edit",
        name: "invoice-edit",
        component: () => import("@/views/utility/invoice/invoice-edit"),
        meta: {
          groupParent: "Utility",
          role: ['admin', 'super-admin']
        },
      },
      {
        path: "invoice-add",
        name: "invoice-add",
        component: () => import("@/views/utility/invoice/invoice-add"),
        meta: {
          groupParent: "Utility",
          role: ['admin', 'super-admin']
        },
      },
      {
        path: "Pricing",
        name: "pricing",
        component: () => import("@/views/utility/pricing"),
        meta: {
          groupParent: "Utility",
          role: ['admin', 'super-admin']
        },
      },
      {
        path: "faq",
        name: "faq",
        component: () => import("@/views/utility/faq"),
        meta: {
          groupParent: "Utility",
          role: ['admin', 'super-admin']
        },
      },
      {
        path: "blog",
        name: "blog",
        component: () => import("@/views/utility/blog"),
        meta: {
          groupParent: "Utility",
          role: ['admin', 'super-admin']
        },
      },
      {
        path: "blog-details",
        name: "blog-details",
        component: () => import("@/views/utility/blog/blog-details"),
        meta: {
          groupParent: "Utility",
          role: ['admin', 'super-admin']
        },
      },
      {
        path: "testimonial",
        name: "testimonial",
        component: () => import("@/views/utility/testimonial"),
        meta: {
          groupParent: "Utility",
          role: ['admin', 'super-admin']
        },
      },
      {
        path: "map",
        name: "map",
        component: () => import("@/views/map"),
        meta: {
          role: ['admin', 'super-admin']
        },
      },
      {
        path: "profile",
        name: "profile",
        component: () => import("@/views/profile.vue"),
      },
      {
        path: "profile-setting",
        name: "profile-setting",
        component: () => import("@/views/profile.vue"),
      },
      {
        path: "settings",
        name: "settings",
        component: () => import("@/views/settings.vue"),
      },
      {
        path: "icons",
        name: "icons",
        component: () => import("@/views/icons.vue"),
      },
    ],
  },
  {
    path: "/:catchAll(.*)",
    name: "404",
    component: () => import("@/views/404.vue"),
  },
  {
    path: "/coming-soon",
    name: "coming-soon",
    component: () => import("@/views/utility/comming-soon"),
  },
  {
    path: "/under-construction",
    name: "under-construction",
    component: () => import("@/views/utility/under-construction"),
  },
  {
    path: "/error",
    name: "error",
    component: () => import("@/views/error.vue"),
  },
];

// Aplica middleware automaticamente nas rotas que têm meta.role mas não têm meta.middleware
function applyMiddlewareToRoleRoutes(routeArray) {
  return routeArray.map(route => {
    if (route.children) {
      route.children = applyMiddlewareToRoleRoutes(route.children)
    }

    if (route.meta?.role && !route.meta?.middleware) {
      route.meta.middleware = [auth]
    }

    return route
  })
}

export default applyMiddlewareToRoleRoutes(routes)
