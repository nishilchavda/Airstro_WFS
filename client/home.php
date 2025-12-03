<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Airstro | Book Flights</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<section class="hero">
  <div class="container">
    <div>
      <div class="hero-eyebrow">AIRSTRO FLIGHT SERVICES</div>
      <h1 class="hero-title">Book domestic & international flights at smart fares.</h1>
      <p class="hero-sub">
        Search, compare and book flights in just a few clicks. Manage your trips, check status
        and download tickets – all in one place.
      </p>

      <div class="hero-pills">
        <span>✓ On-time performance</span>
        <span>✓ Easy reschedule & cancellation</span>
        <span>✓ Secure payments</span>
      </div>
    </div>
  </div>

  <!-- Search Card -->
  <div class="container search-card-wrapper">
    <div class="search-card">
      <div class="search-tabs">
        <button class="search-tab active" type="button">One-way</button>
        <button class="search-tab" type="button">Round-trip</button>
        <span class="badge-soft">Best prices on early bookings</span>
      </div>

      <form class="search-form" action="search_results.php" method="GET">
        <input type="hidden" name="trip" id="tripType" value="oneway">

        <div class="form-field">
          <label class="form-label">From</label>
          <input class="form-input" type="text" name="from" placeholder="e.g. Mumbai (BOM)" required>
        </div>

        <div class="form-field">
          <label class="form-label">To</label>
          <input class="form-input" type="text" name="to" placeholder="e.g. Delhi (DEL)" required>
        </div>

        <div class="form-field">
          <label class="form-label">Departure</label>
          <input class="form-input" type="date" name="departure" required>
        </div>

        <div class="form-field return-date-field" style="display:none;">
          <label class="form-label">Return</label>
          <input class="form-input" type="date" name="return">
        </div>

        <div class="form-field">
          <label class="form-label">Travellers</label>
          <input class="form-input" type="number" min="1" value="1" name="passengers">
        </div>

        <div class="form-field" style="grid-column:1/-1; display:flex; justify-content:flex-end;">
          <button class="btn btn-primary" type="submit">Search Flights</button>
        </div>
      </form>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <h2 class="section-title" style="margin-bottom: 16px;">
      Find exciting offers and best deals
    </h2>

    <div class="offers-grid">
      <div class="offer-card">
        <img src="../assets/img/1offer.png" alt="SBI Credit Card Offer">
      </div>
      <div class="offer-card">
        <img src="../assets/img/2offer.png" alt="Sightseeing 50% Off">
      </div>
      <div class="offer-card">
        <img src="../assets/img/3offer.png" alt="Hotels Black Friday Sale">
      </div>
      <div class="offer-card">
        <img src="../assets/img/4offer.png" alt="Destination Offers">
      </div>
    </div>
  </div>
</section>


<section class="banner-slider-section">
  <div class="banner-slider">
    <img src="../assets/img/1offerbanner.png" class="banner-slide active">
    <img src="../assets/img/2offerbanner.png" class="banner-slide">
    <img src="../assets/img/3offerbanner.png" class="banner-slide">

    <!-- Pagination dots -->
    <div class="banner-dots">
      <span class="dot active" onclick="setSlide(0)"></span>
      <span class="dot" onclick="setSlide(1)"></span>
      <span class="dot" onclick="setSlide(2)"></span>
    </div>
  </div>
</section>


<section class="section">
  <div class="container">
    <h2 class="section-title" style="margin-bottom: 16px;">
      Embark on a journey of inspiration with Airstro
    </h2>

    <div style="
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 12px;
    ">

      <!-- Destination cards -->
      <div class="dest-card">
        <img src="https://www.goindigo.in/content/dam/s6web/in/en/assets/homepage/city/m-web/Ayodhya.jpg" alt="Ayodhya"
             class="dest-img">
        <div class="dest-overlay"><span>Ayodhya, Uttar Pradesh</span></div>
      </div>

      <div class="dest-card">
        <img src="https://www.goindigo.in/content/dam/s6web/in/en/assets/homepage/city/m-web/Bangkok.jpg" alt="Bangkok"
             class="dest-img">
        <div class="dest-overlay"><span>Bangkok, Thailand</span></div>
      </div>

      <div class="dest-card">
        <img src="https://www.goindigo.in/content/dam/s6web/in/en/assets/homepage/city/m-web/Bengaluru.jpg" alt="Bengaluru"
             class="dest-img">
        <div class="dest-overlay"><span>Bengaluru</span></div>
      </div>

      <div class="dest-card">
        <img src="https://www.goindigo.in/content/dam/s6web/in/en/assets/homepage/city/m-web/Delhi.jpg" alt="Delhi"
             class="dest-img">
        <div class="dest-overlay"><span>Delhi</span></div>
      </div>

      <div class="dest-card">
        <img src="https://www.goindigo.in/content/dam/s6web/in/en/assets/homepage/city/m-web/Goa.jpg" alt="Goa"
             class="dest-img">
        <div class="dest-overlay"><span>Goa</span></div>
      </div>

      <div class="dest-card">
        <img src="https://www.goindigo.in/content/dam/s6web/in/en/assets/homepage/city/m-web/Hyderabad.jpg" alt="Hyderabad"
             class="dest-img">
        <div class="dest-overlay"><span>Hyderabad, Telangana</span></div>
      </div>

    </div>
  </div>
</section>


<script>
const tabs = document.querySelectorAll('.search-tab');
const tripInput = document.getElementById('tripType');
const returnField = document.querySelector('.return-date-field');

tabs.forEach((tab, idx) => {
  tab.addEventListener('click', () => {
    tabs.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    if (idx === 1) {
      tripInput.value = 'round';
      returnField.style.display = "block";
    } else {
      tripInput.value = 'oneway';
      returnField.style.display = "none";
    }
  });
});

let slideIndex = 0;
const slides = document.querySelectorAll(".banner-slide");
const dots = document.querySelectorAll(".dot");

function showSlide(i) {
  slides.forEach((slide) => slide.classList.remove("active"));
  dots.forEach((dot) => dot.classList.remove("active"));
  slides[i].classList.add("active");
  dots[i].classList.add("active");
  slideIndex = i;
}

function nextSlide() {
  slideIndex = (slideIndex + 1) % slides.length;
  showSlide(slideIndex);
}

function setSlide(i) {
  slideIndex = i;
  showSlide(i);
}

setInterval(nextSlide, 10000); // auto slide every 10s
</script>


<?php include 'footer.php'; ?>
</body>
</html>
