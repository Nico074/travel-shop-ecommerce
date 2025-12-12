<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - Lpa Travel Shop</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <section class="about-hero">
    <div class="about-hero__content">
      <p class="eyebrow">Lpa Travel Shop</p>
      <h1>Built for curious travelers</h1>
      <p class="lede">We curate reliable gear so your next weekend escape, mountain hike, or round-the-world trip is covered.</p>
    </div>
  </section>

  <section class="about-page">
    <div class="about-card">
      <h2>Who we are</h2>
      <p>We are a small team of explorers and gear testers based in Brisbane. Every pack we stock is chosen for comfort, every tent for weather resistance, and every jacket for real-world warmth. Our goal is simple: make it easy to grab the right equipment and get outside faster.</p>
    </div>

    <div class="about-grid">
      <div class="about-item">
        <h3>Backpacks for every route</h3>
        <p>From daypacks to travel haulers, our backpack range is picked for balanced weight, smart pockets, and all-day carry comfort.</p>
        <a class="text-link" href="products/backpacks.php">Explore backpacks</a>
      </div>
      <div class="about-item">
        <h3>Tents that handle weather</h3>
        <p>Whether you camp in coastal breezes or alpine gusts, our tents focus on quick setup, solid poles, and waterproof flysheets.</p>
        <a class="text-link" href="products/tents.php">See our tents</a>
      </div>
      <div class="about-item">
        <h3>Jackets that earn their keep</h3>
        <p>Layer up with insulated puffers, rain shells, and softshells tested for breathability and real protection on the trail.</p>
        <a class="text-link" href="products/jackets.php">Browse jackets</a>
      </div>
    </div>

    <div class="about-card">
      <h2>How we work</h2>
      <p>We try gear in the field, listen to traveler feedback, and keep our catalog focused on items that perform season after season. When you add something to cart, you are picking from equipment we actually trust.</p>
    </div>
  </section>

  <?php include 'includes/footer.php'; ?>
</body>
</html>
