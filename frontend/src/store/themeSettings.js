import { defineStore } from "pinia";

export const useThemeSettingsStore = defineStore("themeSettings", {
  state: () => ({
    sidebarCollasp: false,
    sidebarHidden: false,
    mobielSidebar: false,
    semidark: false,
    monochrome: false,
    semiDarkTheme: "semi-light",
    isDark: false,
    skin: "default",
    theme: "light",
    isOpenSettings: false,
    cWidth: "full",
    menuLayout: "vertical",
    navbarType: "sticky",
    isMouseHovered: false,
    footerType: "static",
    direction: false,
    cartOpener: false,
    chartColors: {
      title: "red",
    },
  }),

  actions: {
    setSidebarCollasp() {
      this.sidebarCollasp = !this.sidebarCollasp;
    },

    toggleDark() {
      this.isDark = !this.isDark;

      document.body.classList.remove(this.theme);
      this.theme = this.theme === "dark" ? "light" : "dark";
      document.body.classList.add(this.theme);

      localStorage.setItem("theme", this.theme);
    },

    toggleMonochrome() {
      const html = document.documentElement;

      if (localStorage.getItem("monochrome")) {
        localStorage.removeItem("monochrome");
        html.classList.remove("grayscale");
      } else {
        localStorage.setItem("monochrome", "true");
        html.classList.add("grayscale");
      }
    },

    toggleSettings() {
      this.isOpenSettings = !this.isOpenSettings;
    },

    toggleMsidebar() {
      this.mobielSidebar = !this.mobielSidebar;
    },

    toggleSemiDark() {
      this.semidark = !this.semidark;
      this.semiDarkTheme = this.semidark ? "semi-dark" : "semi-light";

      document.body.classList.toggle("semi-dark");
      localStorage.setItem("semiDark", String(this.semidark));
    },

    toggleCartDrawer() {
      this.cartOpener = !this.cartOpener;
    },
  },
});
