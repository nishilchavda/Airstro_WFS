<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Airstro | Login</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="auth-page">
  <div class="auth-card">
    <div class="tag">Login</div>
    <h1 class="auth-title">Welcome back</h1>
    <p class="auth-subtitle">Continue to your account.</p>

    <?php if (!empty($_SESSION['error'])): ?>
      <p style="color:#b91c1c;font-size:12px;margin-bottom:8px;">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
      </p>
    <?php endif; ?>

    <form class="auth-form" action="../config/login.php" method="POST">
      <div class="form-field">
        <label class="form-label" for="email">Email</label>
        <input class="form-input" type="email" name="email" id="email" required>
      </div>
      <div class="form-field">
        <label class="form-label" for="password">Password</label>
        <input class="form-input" type="password" name="password" id="password" required>
      </div>

      <button class="btn btn-primary" type="submit" style="width:100%;justify-content:center;">
        Login
      </button>
    </form>

    <p class="auth-footer-text">
      Don't have an account? <a href="register.php">Create account</a>
    </p>
  </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
