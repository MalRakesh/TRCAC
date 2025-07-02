// Counter Animation on Scroll
const counters = document.querySelectorAll(".counter");
let animated = false;

function animateCounter(counter) {
  const targetText = counter.getAttribute("data-target");
  const target = parseInt(targetText.replace(/[^0-9]/g, "")) || 0;

  let count = 0;
  const duration = 2000; // total animation time in ms
  const startTime = performance.now();

  function updateCount(currentTime) {
    const elapsedTime = currentTime - startTime;
    const progress = Math.min(elapsedTime / duration, 1);
    const increment = Math.floor(progress * target);

    counter.innerText = increment.toLocaleString();

    if (progress < 1) {
      requestAnimationFrame(updateCount);
    } else {
      // Append '+' if present
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
    window.removeEventListener("scroll", handleScroll); // Run once
  }
}

// On page load, check if section is already visible
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
  themeLink.setAttribute(
    "href",
    document.body.classList.contains("dark-mode")
      ? "styles/dark.css"
      : "styles/light.css"
  );
});

// Scroll Top Button
window.addEventListener("scroll", () => {
  const btn = document.querySelector(".scroll-top");
  btn.classList.toggle("show", window.scrollY > 300);
});

// AOS Init (only if loaded)
if (typeof AOS !== "undefined") {
  AOS.init();
}

// Mobile Menu Toggle
document.querySelector(".hamburger").addEventListener("click", () => {
  document.querySelector(".nav-links").classList.toggle("active");
});
