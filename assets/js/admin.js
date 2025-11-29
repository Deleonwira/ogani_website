document.addEventListener("DOMContentLoaded", () => {
  const toggleSidebar = () => document.body.classList.toggle("sidebar-open");
  document.querySelectorAll("[data-toggle='sidebar']").forEach((btn) => {
    btn.addEventListener("click", toggleSidebar);
  });

  const searchInput = document.querySelector(".admin-search input");
  if (searchInput) {
    window.addEventListener("keydown", (event) => {
      if (event.key === "/" && document.activeElement !== searchInput) {
        event.preventDefault();
        searchInput.focus();
      }
      if (event.key === "Escape" && document.activeElement === searchInput) {
        searchInput.blur();
      }
    });
  }

  const animatedBlocks = document.querySelectorAll("[data-animate-stagger]");
  if (animatedBlocks.length) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.2 },
    );
    animatedBlocks.forEach((block) => observer.observe(block));
  }
});
