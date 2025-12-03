<?php
// client/navbar.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['client_id']);
$clientName = $isLoggedIn ? ($_SESSION['client_name'] ?? 'Traveler') : null;

// For active menu highlighting
$current = basename($_SERVER['PHP_SELF']);
?>
<link rel="stylesheet" href="../assets/style.css">

<nav class="navbar">
  <div class="container navbar-inner">
    <div class="nav-left">
      <div class="logo-mark">A</div>
      <div class="logo-text">
        <span>Airstro</span>
        <span>Fly smart. Go further.</span>
      </div>

      <div class="nav-links">
        <a href="home.php" class="<?php echo $current === 'home.php' ? 'active' : ''; ?>">
          Book
        </a>
        <a href="status.php" class="<?php echo $current === 'status.php' ? 'active' : ''; ?>">
          Flight Status
        </a>
        <a href="mybookings.php" class="<?php echo $current === 'mybookings.php' ? 'active' : ''; ?>">
          My Trips
        </a>
        <a href="about.php" class="<?php echo $current === 'about.php' ? 'active' : ''; ?>">
          About
        </a>
        <?php if (isset($_SESSION['admin_id'])): ?>
  <a href="../admin/flights.php" class="nav-link">Admin Dashboard</a>
<?php endif; ?>
      </div>
    </div>

    <div class="nav-right">
      <?php if ($isLoggedIn): ?>
        <button class="btn btn-outline">
          Hi, <?php echo htmlspecialchars($clientName); ?>
        </button>
        <a href="logout.php">
          <button class="btn btn-primary">Logout</button>
        </a>
      <?php else: ?>
        <a href="login.php">
          <button class="btn btn-outline">Log in</button>
        </a>
        <a href="register.php">
          <button class="btn btn-primary">Sign up</button>
        </a>
      <?php endif; ?>
    </div>
  </div>
</nav>
