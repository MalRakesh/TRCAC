// Counter Animation on Scroll
const counters = document.querySelectorAll(".counter");
const speed = 200; // Total steps (not duration)
let animated = false;

function animateCounter(counter) {
  const targetText = counter.getAttribute("data-target");
  const target = parseInt(targetText.replace(/[^0-9]/g, "")) || 0;
  let count = 0;

  function updateCount() {
    const step = Math.ceil(target / speed);
    if (count < target) {
      count += step;
      counter.innerText = count.toLocaleString();
      requestAnimationFrame(updateCount);
    } else {
      counter.innerText =
        target.toLocaleString() + (targetText.includes("+") ? "+" : "");
    }
  }

  updateCount();
}

// Scroll Observer
const statsSection = document.querySelector(".why-choose-us");

function isScrolledIntoView(el) {
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

// AOS Init
AOS.init();

// Mobile Menu Toggle
document.querySelector(".hamburger").addEventListener("click", () => {
  document.querySelector(".nav-links").classList.toggle("active");
});
