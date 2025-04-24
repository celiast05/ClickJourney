// Liste des thèmes disponibles et leur fichier CSS
const THEMES = [
    { name: 'light',    file: 'css/themes/theme_light.css' },
    { name: 'dark',     file: 'css/themes/theme_dark.css' },
    { name: 'contrast', file: 'css/themes/theme_contrast.css' },
    { name: 'large',    file: 'css/themes/theme_large.css' }
  ];
  
  // Nom du cookie et durée
  const COOKIE_NAME = 'theme';
  const COOKIE_DAYS = 30;
  
  // ➤ Crée un cookie
  function setCookie(name, value, days) {
    const expires = new Date(Date.now() + days * 86400000).toUTCString();
    document.cookie = `${name}=${value}; expires=${expires}; path=/`;
  }
  
  // ➤ Récupère un cookie
  function getCookie(name) {
    const m = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return m ? m[2] : null;
  }
  
  // ➤ Applique un thème (change le href du <link id="theme-link">)
  function applyTheme(themeName) {
    const theme = THEMES.find(t => t.name === themeName);
    if (!theme) return;
    const themeLink = document.getElementById('theme-link');
    if (themeLink) {
      themeLink.href = theme.file;
    }
  }
  
  // ➤ Donne l’index du thème actuel dans la liste
  function currentThemeIndex() {
    const saved = getCookie(COOKIE_NAME) || THEMES[0].name;
    return THEMES.findIndex(t => t.name === saved);
  }
  
  // ➤ Passe au thème suivant
  function cycleTheme() {
    let idx = currentThemeIndex();
    idx = (idx + 1) % THEMES.length;
    const nextTheme = THEMES[idx].name;
    applyTheme(nextTheme);
    setCookie(COOKIE_NAME, nextTheme, COOKIE_DAYS);
  }
  
  // ➤ Exécution au chargement du DOM
  document.addEventListener("DOMContentLoaded", () => {
    // Appliquer le thème stocké dans le cookie, ou le thème par défaut
    const savedTheme = getCookie(COOKIE_NAME);
    const validThemes = THEMES.map(t => t.name);
    if (validThemes.includes(savedTheme)) {
      applyTheme(savedTheme);
    } else {
      applyTheme(THEMES[0].name);
      setCookie(COOKIE_NAME, THEMES[0].name, COOKIE_DAYS);
    }
  
    // Bouton de changement de thème
    const btn = document.getElementById('change-theme');
    if (btn) {
      btn.addEventListener('click', cycleTheme);
    }
  });
  