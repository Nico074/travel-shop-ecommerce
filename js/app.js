/* ============================================================
   HERO (FIXED IMAGE)
   ============================================================ */

// Use a single image only - no carousel
const heroElement = document.getElementById("hero-image");

if (heroElement) {
  heroElement.src = "images/travel2.jpg";
  heroElement.style.objectPosition = "center center";
}
