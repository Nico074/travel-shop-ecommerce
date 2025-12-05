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
  <title>Contact - Lpa Travel Shop</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <main class="contact-page">
    <section class="contact-card">
      <div class="contact-intro">
        <h1>Need help, Contact us!</h1>
        <p>You can follow us on social media and discover our products. For any questions, don't hesitate to contact us; our online services are available 24/7.</p>
      </div>

      <div class="contact-actions">
        <a class="contact-item" href="mailto:lpatravelshop@outlook.com.au">
          <span class="icon-badge">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm0 0 8 6 8-6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </span>
          <span class="contact-label">lpatravelshop@outlook.com.au</span>
        </a>

        <a class="contact-item" href="tel:+61470555218">
          <span class="icon-badge">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M6.1 3.5 8.7 6a1 1 0 0 1 .2 1.1l-1 2.4a1 1 0 0 0 .2 1l5.6 5.6a1 1 0 0 0 1 .2l2.4-1a1 1 0 0 1 1.1.2l2.5 2.6a1 1 0 0 1 0 1.4l-1.6 1.7c-.7.7-1.8 1-2.8.7-2.6-.7-5.7-2.9-8.5-5.7-2.8-2.8-5-5.9-5.7-8.5-.3-1 0-2 .7-2.8L4.7 3.5a1 1 0 0 1 1.4 0Z" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </span>
          <span class="contact-label">+61 470 555 218</span>
        </a>

        <a class="contact-item" href="https://www.instagram.com/lpa_travel_shop" target="_blank" rel="noopener">
          <span class="icon-badge">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <rect x="4" y="4" width="16" height="16" rx="4" ry="4" fill="none" stroke="currentColor" stroke-width="1.5"/>
              <circle cx="12" cy="12" r="3.5" fill="none" stroke="currentColor" stroke-width="1.5"/>
              <circle cx="17" cy="7" r="1" fill="currentColor"/>
            </svg>
          </span>
          <span class="contact-label">Instagram: Lpa Travel Shop</span>
        </a>

        <a class="contact-item" href="https://www.facebook.com/lpatravelshop" target="_blank" rel="noopener">
          <span class="icon-badge">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M15 4h2V1h-2a5 5 0 0 0-5 5v3H7v3h3v8h3v-8h3l1-3h-4V6a2 2 0 0 1 2-2Z" fill="currentColor"/>
            </svg>
          </span>
          <span class="contact-label">Facebook: Lpa Travel Shop</span>
        </a>
      </div>
    </section>
  </main>

  <?php include 'includes/footer.php'; ?>
</body>
</html>
