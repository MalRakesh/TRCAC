// Counter Animation on Scroll
const counters = document.querySelectorAll(".counter");
const speed = 200;

function animateCounter(counter) {
  const targetText = counter.getAttribute("data-target");
  const target = parseInt(targetText.replace(/[^0-9]/g, "")) || 0;
  let count = 0;

  const updateCount = () => {
    const inc = Math.ceil(target / speed);

    if (count < target) {
      count += inc;
      counter.innerText = count.toLocaleString();
      setTimeout(updateCount, 1);
    } else {
      if (targetText.includes("+")) {
        counter.innerText = target.toLocaleString() + "+";
      } else {
        counter.innerText = target.toLocaleString();
      }
    }
  };

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

  if (
    isScrolledIntoView(statsSection) &&
    !statsSection.classList.contains("animated")
  ) {
    counters.forEach(animateCounter);
    statsSection.classList.add("animated");
    window.removeEventListener("scroll", handleScroll); // Run once
  }
}

// On load, check if section is already visible
window.addEventListener("load", () => {
  if (statsSection && isScrolledIntoView(statsSection)) {
    counters.forEach(animateCounter);
    statsSection.classList.add("animated");
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
