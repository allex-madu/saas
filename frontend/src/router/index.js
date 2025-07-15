import { createRouter, createWebHistory } from "vue-router";
import middlewarePipeline from "../middleware/middlewarePipeline";
import routes from "./route";
import { makeServer } from "../server";

const router = createRouter({
  history: createWebHistory(import.meta.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else {
      return { top: 0 };
    }
  },
});

router.beforeEach((to, from, next) => {
  // Atualizar título da aba
  const titleText = to.name ? String(to.name) : "Página";
  const words = titleText.split(/[-_]/);
  const capitalized = words.map(w => w[0].toUpperCase() + w.slice(1));
  document.title = "Dashcode - " + capitalized.join(" ");

  // Middleware
  const middleware = to.meta.middleware;
  if (!middleware || middleware.length === 0) {
    return next();
  }

  const context = { to, from, next };
  return middleware[0]({
    ...context,
    next: middlewarePipeline(context, middleware, 1),
  });
});

router.afterEach(() => {
  const appLoading = document.getElementById("loading-bg");
  if (appLoading) {
    appLoading.style.display = "none";
  }
  makeServer(); // MirageJS
});

export default router;
