<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Airstro | Create Account</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="auth-page">
  <div class="auth-card">
    <div class="tag">Create account</div>
    <h1 class="auth-title">Join Airstro</h1>
    <p class="auth-subtitle">Save your details and manage your trips easily.</p>

    <?php if (!empty($_SESSION['error'])): ?>
      <p style="color:#b91c1c;font-size:12px;margin-bottom:8px;">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
      </p>
    <?php endif; ?>

    <form class="auth-form" action="../config/register_client.php" method="POST">
      <div class="form-field">
        <label class="form-label" for="name">Full name</label>
        <input class="form-input" type="text" name="name" id="name" required>
      </div>

      <div class="form-field">
        <label class="form-label" for="email">Email</label>
        <input class="form-input" type="email" name="email" id="email" required>
      </div>

      <div class="form-field">
        <label class="form-label" for="password">Password</label>
        <input class="form-input" type="password" name="password" id="password" required>
      </div>

      <button class="btn btn-primary" type="submit" style="width:100%;justify-content:center;">
        Create account
      </button>
    </form>

    <p class="auth-footer-text">
      Already have an account?
      <a href="login.php">Log in</a>
    </p>
  </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
