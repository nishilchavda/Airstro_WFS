<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Airstro | About Us</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="about-hero-bg">
  <div class="container">
    <p class="about-tag">ABOUT US</p>
    <h1 class="about-title">Airstro BluChips Loyalty Program</h1>
    <p class="about-sub">
      Fly, earn and enjoy exclusive travel rewards with Airstro BluChips – our simple and rewarding
      loyalty program designed for frequent flyers, family vacations and business trips alike.
    </p>
  </div>
</div>

<!-- SECTION 1 -->
<section class="container about-section">
  <h2 class="about-heading">
    Sign up and get access to <span class="blue">exciting privileges and benefits</span>
  </h2>

  <div class="benefits-cards">
    <div class="benefit-card">
      <img src="../assets/img/airstro.avif" alt="">
      <div class="benefit-overlay">
        <h3>Earn Airstro BluChips</h3>
        <p>Earn BluChips on every Airstro flight and select add-ons like seats, meals and baggage.</p>
      </div>
    </div>

    <div class="benefit-card">
      <img src="../assets/img/blockoutdates.avif" alt="">
      <div class="benefit-overlay">
        <h3>No blackout dates</h3>
        <p>Redeem BluChips on any eligible flight — no blackout dates, no restrictions.</p>
      </div>
    </div>

    <div class="benefit-card">
      <img src="https://images.pexels.com/photos/39396/hourglass-time-hours-sand-39396.jpeg" alt="">
      <div class="benefit-overlay">
        <h3>Lifetime validity</h3>
        <p>Keep flying and your BluChips remain active — forever.</p>
      </div>
    </div>
  </div>

  <div style="display:flex; justify-content:center; margin-top:20px;">
    <button class="btn btn-primary">Enroll Now ✈️</button>
  </div>
</section>

<!-- SECTION 2 -->
<section class="container about-section">
  <h2 class="about-heading">
    Learn more about <span class="blue">Earning</span> and <span class="blue">Redeeming</span> BluChips
  </h2>

  <div class="learn-cards">
    <div class="learn-card">
      <img src="../assets/img/How-to-earn.avif" alt="">
      <div class="learn-overlay">
        <h3>Read more about earning BluChips</h3>
        <p>Earn BluChips on flights and add-ons such as seats, meals and baggage.</p>
        <div class="arrow-pill">↗</div>
      </div>
    </div>

    <div class="learn-card">
      <img src="../assets/img/airstro.avif" alt="">
      <div class="learn-overlay">
        <h3>Read more about redeeming BluChips</h3>
        <p>Redeem BluChips on Airstro flights, upgrades and more — anytime.</p>
        <div class="arrow-pill">↗</div>
      </div>
    </div>
  </div>
</section>

<!-- SECTION 3 STATS -->
<section class="about-stats-section">
  <div class="container">
    <h2 class="about-heading center">Airstro at a glance</h2>

    <div class="stats-grid">
      <div class="stats-big">
        <div class="big-stats-value">2,200+</div>
        <div class="big-stats-label">Daily<br>Flights</div>
        <div class="big-plane-icon">✈️</div>
      </div>

      <div class="stats-list">
        <div class="stats-card"><span class="value">90+</span> <span class="label">Domestic Destinations</span></div>
        <div class="stats-card"><span class="value">40+</span> <span class="label">International Destinations</span></div>
        <div class="stats-card"><span class="value">750 Mn+</span> <span class="label">Happy Customers</span></div>
        <div class="stats-card"><span class="value">400+</span> <span class="label">Fleet Strong</span></div>
      </div>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
</body>
</html>
