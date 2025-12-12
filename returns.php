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
  <title>Returns & Shipping - Lpa Travel Shop</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <main class="policy-page">
    <div class="policy-hero">
      <h1>Returns & Shipping</h1>
      <p>Simple delivery options, easy returns. Shop with confidence.</p>
    </div>

    <section class="policy-grid">
      <article class="policy-card">
        <h2>Shipping</h2>
        <ul>
          <li><strong>Standard:</strong> 3-5 business days, tracked.</li>
          <li><strong>Express:</strong> 1-2 business days on eligible items.</li>
          <li><strong>Cost:</strong> Calculated at checkout by weight and destination.</li>
          <li><strong>Tracking:</strong> Email updates with every step.</li>
        </ul>
      </article>

      <article class="policy-card">
        <h2>Returns</h2>
        <ul>
          <li><strong>Window:</strong> 30 days from delivery.</li>
          <li><strong>Condition:</strong> Unused, with tags/packaging.</li>
          <li><strong>Process:</strong> Start a request via your orders; we email a label.</li>
          <li><strong>Fees:</strong> Return shipping deducted unless item is faulty.</li>
        </ul>
      </article>

      <article class="policy-card">
        <h2>Exchanges</h2>
        <p>Need a different size or color? Return your item and place a new order so stock is reserved for you.</p>
      </article>

      <article class="policy-card">
        <h2>Questions?</h2>
        <p>Contact our support team for sizing advice, stock checks, or warranty help.</p>
        <a class="text-link" href="contact.php">Contact support</a>
      </article>
    </section>
  </main>

  <?php include 'includes/footer.php'; ?>
</body>
</html>
