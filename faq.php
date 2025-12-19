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
  <title>FAQ - Lpa Travel Shop</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>

  <?php include 'includes/header.php'; ?>

  <section class="faq-hero">
    <div class="faq-hero__content">
      <p class="eyebrow">Support</p>
      <h1>Frequently Asked Questions</h1>
      <p class="lede">Quick answers about orders, shipping, returns, and gear recommendations.</p>
    </div>
  </section>

  <section class="faq-page">
    <div class="faq-grid">
      <div class="faq-card">
        <h2>Orders & Payment</h2>
        <details class="faq-item">
          <summary>How do I track my order?</summary>
          <div class="faq-answer">
            Use the tracking link in your confirmation email. Updates appear within 24 hours.
          </div>
        </details>
        <details class="faq-item">
          <summary>Which payment methods do you accept?</summary>
          <div class="faq-answer">
            We accept Visa, Mastercard, PayPal, and major debit cards.
          </div>
        </details>
        <details class="faq-item">
          <summary>Can I change or cancel an order?</summary>
          <div class="faq-answer">
            If the order has not shipped, we can update it. Contact us within 2 hours.
          </div>
        </details>
      </div>

      <div class="faq-card">
        <h2>Shipping & Delivery</h2>
        <details class="faq-item">
          <summary>How long does delivery take?</summary>
          <div class="faq-answer">
            Standard delivery is 3-6 business days. Express is 1-2 business days.
          </div>
        </details>
        <details class="faq-item">
          <summary>Do you ship internationally?</summary>
          <div class="faq-answer">
            Yes, we ship to most countries. Rates are shown at checkout.
          </div>
        </details>
        <details class="faq-item">
          <summary>My parcel is late. What should I do?</summary>
          <div class="faq-answer">
            Contact us with your order number and we will check with the carrier.
          </div>
        </details>
      </div>

      <div class="faq-card">
        <h2>Returns & Warranty</h2>
        <details class="faq-item">
          <summary>What is your return policy?</summary>
          <div class="faq-answer">
            Unused items can be returned within 30 days for a refund or store credit.
          </div>
        </details>
        <details class="faq-item">
          <summary>How do I start a return?</summary>
          <div class="faq-answer">
            Visit the Returns & Shipping page and follow the steps to print a label.
          </div>
        </details>
        <details class="faq-item">
          <summary>Do your products have a warranty?</summary>
          <div class="faq-answer">
            Yes, all products include a 12-month warranty against defects.
          </div>
        </details>
      </div>

      <div class="faq-card">
        <h2>Gear Guidance</h2>
        <details class="faq-item">
          <summary>Which backpack size should I pick?</summary>
          <div class="faq-answer">
            20-30L for day trips, 40-55L for weekend travel, 60L+ for long adventures.
          </div>
        </details>
        <details class="faq-item">
          <summary>Are your tents suitable for rain?</summary>
          <div class="faq-answer">
            Our tents are rated for light to moderate rain with sealed seams.
          </div>
        </details>
        <details class="faq-item">
          <summary>Can I get help choosing gear?</summary>
          <div class="faq-answer">
            Yes, send us your trip details and we will recommend a setup.
          </div>
        </details>
      </div>
    </div>
  </section>

  <?php include 'includes/footer.php'; ?>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const cards = document.querySelectorAll(".faq-card");
      cards.forEach(card => {
        const items = card.querySelectorAll(".faq-item");
        items.forEach(item => {
          item.addEventListener("toggle", () => {
            if (!item.open) {
              return;
            }
            items.forEach(other => {
              if (other !== item) {
                other.open = false;
              }
            });
          });
        });
      });
    });
  </script>
</body>
</html>
