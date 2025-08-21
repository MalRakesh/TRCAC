// =================== COUNTER ANIMATION ===================
const counters = document.querySelectorAll(".counter");
let animated = false;

function animateCounter(counter) {
  const targetText = counter.getAttribute("data-target");
  const target = parseInt(targetText.replace(/[^0-9]/g, ""), 10) || 0;

  const duration = 2000;
  const startTime = performance.now();

  function updateCount(currentTime) {
    const elapsedTime = currentTime - startTime;
    const progress = Math.min(elapsedTime / duration, 1);
    const increment = Math.floor(progress * target);

    counter.innerText = increment.toLocaleString();

    if (progress < 1) {
      requestAnimationFrame(updateCount);
    } else {
      counter.innerText =
        target.toLocaleString() + (targetText.includes("+") ? "+" : "");
    }
  }

  requestAnimationFrame(updateCount);
}

// Scroll Detection for Stats
const statsSection = document.querySelector(".why-choose-us");

function isScrolledIntoView(el) {
  if (!el) return false;
  const rect = el.getBoundingClientRect();
  return rect.top <= window.innerHeight - 50 && rect.bottom >= 0;
}

function handleScroll() {
  if (!statsSection) return;
  if (isScrolledIntoView(statsSection) && !animated) {
    counters.forEach(animateCounter);
    animated = true;
    window.removeEventListener("scroll", handleScroll);
  }
}

window.addEventListener("load", () => {
  if (statsSection && isScrolledIntoView(statsSection)) {
    counters.forEach(animateCounter);
    animated = true;
  } else {
    window.addEventListener("scroll", handleScroll);
  }
});

// =================== THEME TOGGLE ===================
const themeToggle = document.querySelector(".toggle-theme");
const themeLink = document.getElementById("theme-style");
const icon = document.querySelector(".toggle-theme i");

if (themeToggle && themeLink) {
  // Use absolute path from root (safe for all pages)
  const basePath = "/frontend/styles/";

  themeToggle.addEventListener("click", () => {
    const isDarkMode = !document.body.classList.contains("dark-mode");
    document.body.classList.toggle("dark-mode", isDarkMode);

    // Update CSS
    themeLink.setAttribute(
      "href",
      isDarkMode ? basePath + "dark.css" : basePath + "light.css"
    );

    // Update icon
    if (isDarkMode) {
      icon.classList.remove("fa-moon");
      icon.classList.add("fa-sun");
    } else {
      icon.classList.remove("fa-sun");
      icon.classList.add("fa-moon");
    }

    // Save to localStorage
    localStorage.setItem("theme", isDarkMode ? "dark" : "light");
  });

  // On Load: Restore saved theme
  window.addEventListener("DOMContentLoaded", () => {
    const savedTheme = localStorage.getItem("theme");
    const isDarkMode = savedTheme === "dark";

    document.body.classList.toggle("dark-mode", isDarkMode);
    themeLink.setAttribute(
      "href",
      isDarkMode ? basePath + "dark.css" : basePath + "light.css"
    );

    if (icon) {
      if (isDarkMode) {
        icon.classList.remove("fa-moon");
        icon.classList.add("fa-sun");
      } else {
        icon.classList.remove("fa-sun");
        icon.classList.add("fa-moon");
      }
    }
  });
}

// =================== SCROLL TOP BUTTON ===================
const scrollTopBtn = document.querySelector(".scroll-top");
if (scrollTopBtn) {
  window.addEventListener("scroll", () => {
    scrollTopBtn.classList.toggle("show", window.scrollY > 300);
  });
}

// =================== MOBILE MENU TOGGLE ===================
const hamburger = document.querySelector(".hamburger");
const navLinks = document.querySelector(".nav-links");

if (hamburger && navLinks) {
  hamburger.addEventListener("click", () => {
    navLinks.classList.toggle("active");
  });
}

// =================== HERO SLIDER (Only on home page) ===================
const hero = document.getElementById("heroSlider");
if (hero) {
  const images = [
    "/frontend/assets/images/trcac_admin-office.jpg",
    "/frontend/assets/images/trcac_entry.jpg",
    "/frontend/assets/images/trcac_building.jpg",
    "/frontend/assets/images/trcac_library.jpg",
    "/frontend/assets/images/trcac_lab.jpg",
  ];
  let currentIndex = 0;

  function changeHeroBackground() {
    hero.style.backgroundImage = `url('${images[currentIndex]}')`;
    currentIndex = (currentIndex + 1) % images.length;
  }

  // Change every 5 seconds
  setInterval(changeHeroBackground, 5000);

  // Set first image on load
  window.addEventListener("load", () => {
    hero.style.backgroundImage = `url('${images[0]}')`;
  });
}

// =================== AOS INIT ===================
if (typeof AOS !== "undefined") {
  AOS.init();
}
