// Counter Animation on Scroll
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

// Scroll Detection
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

// Theme Toggle
document.querySelector(".toggle-theme").addEventListener("click", () => {
  document.body.classList.toggle("dark-mode");
  const themeLink = document.getElementById("theme-style");
  const icon = document.querySelector(".toggle-theme i");

  const isDarkMode = document.body.classList.contains("dark-mode");

  // ✅ Sab jagah relative path: ../../styles/
  const basePath = "../../styles/";

  // Update Theme CSS
  themeLink.setAttribute(
    "href",
    isDarkMode ? "../../styles/dark.css" : "../../styles/light.css"
  );

  if (isDarkMode) {
    icon.classList.remove("fa-moon");
    icon.classList.add("fa-sun");
  } else {
    icon.classList.remove("fa-sun");
    icon.classList.add("fa-moon");
  }

  localStorage.setItem("theme", isDarkMode ? "dark" : "light");
});

// On Page Load: Restore Theme
window.addEventListener("DOMContentLoaded", () => {
  const savedTheme = localStorage.getItem("theme");
  const themeLink = document.getElementById("theme-style");
  const icon = document.querySelector(".toggle-theme i");
  const isDarkMode = savedTheme === "dark";

  // ✅ Sab jagah ../../styles/ use karo
  const basePath = "../../styles/";

  if (isDarkMode) {
    document.body.classList.add("dark-mode");
    themeLink.setAttribute("href", basePath + "dark.css");
    icon.classList.remove("fa-moon");
    icon.classList.add("fa-sun");
  } else {
    document.body.classList.remove("dark-mode");
    themeLink.setAttribute("href", basePath + "light.css");
    icon.classList.remove("fa-sun");
    icon.classList.add("fa-moon");
  }
});

// Scroll Top Button
window.addEventListener("scroll", () => {
  const btn = document.querySelector(".scroll-top");
  btn.classList.toggle("show", window.scrollY > 300);
});

// AOS Init
if (typeof AOS !== "undefined") {
  AOS.init();
}

// Mobile Menu Toggle
document.querySelector(".hamburger").addEventListener("click", () => {
  document.querySelector(".nav-links").classList.toggle("active");
});

// Home Page Slider
const hero = document.getElementById("heroSlider");
const images = [
  "../../assets/images/trcac_admin-office.jpg",
  "../../assets/images/trcac_entry.jpg",
  "../../assets/images/trcac_building.jpg",
  "../../assets/images/trcac_library.jpg",
  "../../assets/images/trcac_lab.jpg",
];
let currentIndex = 0;

function changeHeroBackground() {
  hero.style.backgroundImage = `url('${images[currentIndex]}')`;
  currentIndex = (currentIndex + 1) % images.length;
}

setInterval(changeHeroBackground, 5000);

window.addEventListener("load", () => {
  hero.style.backgroundImage = `url('${images[0]}')`;
});
