// resources/js/categoryScroll.js
document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("categoryScroll");
  const btnLeft = document.getElementById("scrollLeft");
  const btnRight = document.getElementById("scrollRight");

  if (container && btnLeft && btnRight) {
    const scrollAmount = 250; // jarak scroll per klik

    btnLeft.addEventListener("click", () => {
      container.scrollBy({ left: -scrollAmount, behavior: "smooth" });
    });

    btnRight.addEventListener("click", () => {
      container.scrollBy({ left: scrollAmount, behavior: "smooth" });
    });

    // Drag scroll (klik-tahan dan geser)
    let isDown = false;
    let startX;
    let scrollLeft;

    container.addEventListener("mousedown", (e) => {
      isDown = true;
      container.classList.add("cursor-grabbing");
      startX = e.pageX - container.offsetLeft;
      scrollLeft = container.scrollLeft;
    });

    container.addEventListener("mouseleave", () => {
      isDown = false;
      container.classList.remove("cursor-grabbing");
    });

    container.addEventListener("mouseup", () => {
      isDown = false;
      container.classList.remove("cursor-grabbing");
    });

    container.addEventListener("mousemove", (e) => {
      if (!isDown) return;
      e.preventDefault();
      const x = e.pageX - container.offsetLeft;
      const walk = (x - startX) * 1.5; // kecepatan drag
      container.scrollLeft = scrollLeft - walk;
    });
  }
});
