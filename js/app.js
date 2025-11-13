/* ============================================================
   HERO (IMAGE FIXE)
   ============================================================ */

// On définit simplement une image unique — plus de carrousel
const heroElement = document.getElementById("hero-image");

if (heroElement) {
  heroElement.src = "images/brisbane4.jpg";
  heroElement.style.objectPosition = "center center";
}



/* ============================================================
   PRODUITS — DONNÉES DE BASE
   ============================================================ */

const products = [
  { id: 1, name: "Explorer Backpack", price: 79.99, image: "../images/products/explorer_backpack.jpg", category: "backpack" },
  { id: 2, name: "Travel Backpack", price: 89.99, image: "../images/products/travel_backpack.jpg", category: "backpack" },
  { id: 3, name: "Hiking Tent", price: 149.99, image: "../images/products/hiking_tent.jpg", category: "tent" },
  { id: 4, name: "Camping Tent", price: 199.99, image: "../images/products/camping_tent.jpg", category: "tent" },
  { id: 5, name: "Waterproof Jacket", price: 119.99, image: "../images/products/waterproof_jacket.jpg", category: "jacket" },
  { id: 6, name: "Winter Jacket", price: 139.99, image: "../images/products/winter_jacket.jpg", category: "jacket" }
];



/* ============================================================
   RENDU DES PRODUITS (PRODUCTS GRID)
   ============================================================ */

function renderProducts(list) {
  const container = document.getElementById("products-grid");
  if (!container) return; // on ne casse pas les autres pages !

  container.innerHTML = "";

  list.forEach(p => {
    const div = document.createElement("div");
    div.className = "product-card";

    div.innerHTML = `
      <img src="${p.image}" alt="${p.name}">
      <h3>${p.name}</h3>
      <p>$${p.price}</p>
      <button onclick="addToCart(${p.id})">Add to Cart</button>
    `;

    container.appendChild(div);
  });
}



/* ============================================================
   PANIER SIMPLE (FRONT-END)
   ============================================================ */

let cart = [];

function addToCart(id) {
  const product = products.find(p => p.id === id);

  if (product) {
    cart.push(product);
    updateCartCount();
  }
}

function updateCartCount() {
  const btn = document.getElementById("cart-btn");
  if (btn) {
    btn.textContent = `Cart (${cart.length})`;
  }
}



/* ============================================================
   BARRE DE RECHERCHE
   ============================================================ */

function searchProducts() {
  const input = document.getElementById("searchInput");
  if (!input) return;

  const query = input.value.toLowerCase();

  // Recherche uniquement sur pages avec une grille produits
  if (document.getElementById("products-grid")) {
    const results = products.filter(p =>
      p.name.toLowerCase().includes(query)
    );
    renderProducts(results);
  } else {
    alert("Search works only on product pages!");
  }
}


