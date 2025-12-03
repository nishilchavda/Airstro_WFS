<?php
// client/payment.php
session_start();
if (!isset($_SESSION['client_id'])) {
    header('Location: login.php');
    exit;
}
require '../config/db.php';

$booking_id = (int)($_GET['booking_id'] ?? 0);
if ($booking_id <= 0) {
    die('Invalid booking.');
}

$stmt = $pdo->prepare(
  'SELECT b.*, f.flight_no, f.from_city, f.from_code, f.to_city, f.to_code,
          f.departure_datetime, f.arrival_datetime
   FROM bookings b
   JOIN flights f ON b.flight_id = f.id
   WHERE b.id = ? AND b.user_id = ?'
);
$stmt->execute([$booking_id, $_SESSION['client_id']]);
$booking = $stmt->fetch();

if (!$booking) {
    die('Booking not found.');
}

// already paid? go to ticket
if ($booking['payment_status'] === 'PAID') {
    header('Location: ticket.php?id=' . $booking_id);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['method'] ?? 'UPI';

    // Simulate successful payment
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        'UPDATE bookings 
         SET booking_status="CONFIRMED", payment_status="PAID"
         WHERE id = ?'
    );
    $stmt->execute([$booking_id]);

    $stmt = $pdo->prepare(
        'INSERT INTO payments (booking_id, amount, method, status)
         VALUES (?, ?, ?, "SUCCESS")'
    );
    $stmt->execute([$booking_id, $booking['total_amount'], $method]);

    $pdo->commit();

    header('Location: ticket.php?id=' . $booking_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Airstro | Payment</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="bookings-page">
  <div class="container">
    <div class="table-card" style="max-width:520px;margin:0 auto;">
      <h2>Complete your payment</h2>
      <p class="text-muted" style="font-size:12px;margin-bottom:8px;">
        Booking for <?php echo htmlspecialchars($booking['flight_no']); ?> ·
        <?php echo htmlspecialchars($booking['from_code']); ?> → <?php echo htmlspecialchars($booking['to_code']); ?>
      </p>

      <p style="font-size:14px;margin-bottom:8px;">
        Amount to pay:
        <strong>₹<?php echo number_format($booking['total_amount']); ?></strong>
      </p>

      <form method="POST">
        <div class="form-field">
          <label class="form-label">Payment method</label>
          <select class="form-input" name="method">
            <option value="UPI">UPI</option>
            <option value="CARD">Credit / Debit Card</option>
            <option value="NETBANKING">Netbanking</option>
          </select>
        </div>

        <button class="btn btn-primary" type="submit" style="margin-top:10px;width:100%;justify-content:center;">
          Pay now
        </button>
      </form>
    </div>
  </div>
</div>

</body>
</html>
