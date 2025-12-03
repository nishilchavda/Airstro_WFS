<?php
// admin/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simple guard (optional)
// if (!isset($_SESSION['admin_id'])) {
//     header('Location: login.php');
//     exit;
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Airstro Admin</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<nav class="navbar">
  <div class="container navbar-inner">
    <div class="nav-left">
      <div class="logo-mark">A</div>
      <div class="logo-text">
        <span>Airstro Admin</span>
        <span>Manage flights & bookings</span>
      </div>
      <div class="nav-links">
        <a href="flights.php">Flights</a>
        <a href="bookings.php">Bookings</a>
      </div>
    </div>

    <div class="nav-right">
      <?php if (isset($_SESSION['admin_name'])): ?>
        <button class="btn btn-outline">
          Admin: <?php echo htmlspecialchars($_SESSION['admin_name']); ?>
        </button>
      <?php endif; ?>
      <a href="logout.php"><button class="btn btn-primary">Logout</button></a>
    </div>
  </div>
</nav>

<div class="container" style="padding-top: 18px; padding-bottom: 24px;">
