export function setupThemeFromLocalStorage(themeSettingsStore) 
{
  if (localStorage.theme === "dark") {
    document.body.classList.add("dark");
    themeSettingsStore.theme = "dark";
    themeSettingsStore.isDark = true;
  } else {
    document.body.classList.add("light");
    themeSettingsStore.theme = "light";
    themeSettingsStore.isDark = false;
  }

  if (localStorage.semiDark === "true") {
    document.body.classList.add("semi-dark");
    themeSettingsStore.semidark = true;
    themeSettingsStore.semiDarkTheme = "semi-dark";
  } else {
    document.body.classList.add("semi-light");
    themeSettingsStore.semidark = false;
    themeSettingsStore.semiDarkTheme = "semi-light";
  }

  if (localStorage.menuLayout === "horizontal") {
    themeSettingsStore.menuLayout = "horizontal";
  } else {
    themeSettingsStore.menuLayout = "vertical";
  }

  if (localStorage.skin === "bordered") {
    themeSettingsStore.skin = "bordered";
    document.body.classList.add("skin--bordered");
  } else {
    themeSettingsStore.skin = "default";
    document.body.classList.add("skin--default");
  }

  if (localStorage.direction === "true") {
    themeSettingsStore.direction = true;
    document.documentElement.setAttribute("dir", "rtl");
  } else {
    themeSettingsStore.direction = false;
    document.documentElement.setAttribute("dir", "ltr");
  }

  if (localStorage.getItem("monochrome") !== null) {
    themeSettingsStore.monochrome = true;
    document.getElementsByTagName("html")[0].classList.add("grayscale");
  }
}