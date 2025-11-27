// document.addEventListener("DOMContentLoaded", () => {
//     const cartIcon = document.getElementById("cartIcon");
//     const cartModal = document.getElementById("cartModal");
//     const closeCartBtn = document.getElementById("closeCartBtn");

//     if (!cartIcon || !cartModal) {
//         console.warn("Cart modal elements not found.");
//         return;
//     }

//     // Open modal
//     cartIcon.addEventListener("click", () => {
//         cartModal.classList.remove("hidden");
//     });

//     // Close modal (X button)
//     if (closeCartBtn) {
//         closeCartBtn.addEventListener("click", () => {
//             cartModal.classList.add("hidden");
//         });
//     }

//     // Close if clicking the dark backdrop
//     cartModal.addEventListener("click", (e) => {
//         if (e.target === cartModal) {
//             cartModal.classList.add("hidden");
//         }
//     });
// });
