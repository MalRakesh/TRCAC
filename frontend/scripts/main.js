// Counter Animation
const counters = document.querySelectorAll(".counter");
const speed = 200;

counters.forEach((counter) => {
  const updateCount = () => {
    const target = +counter.getAttribute("data-target");
    const count = +counter.innerText;
    const inc = Math.ceil(target / speed);

    if (count < target) {
      counter.innerText = count + inc;
      setTimeout(updateCount, 1);
    } else {
      counter.innerText = target;
    }
  };
  updateCount();
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
