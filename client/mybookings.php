<?php
// client/mybookings.php
session_start();
if (!isset($_SESSION['client_id'])) {
    header('Location: login.php');
    exit;
}
require '../config/db.php';

$stmt = $pdo->prepare(
  'SELECT b.*, f.flight_no, f.from_city, f.from_code, f.to_city, f.to_code,
          f.departure_datetime
   FROM bookings b
   JOIN flights f ON b.flight_id = f.id
   WHERE b.user_id = ?
   ORDER BY b.created_at DESC'
);
$stmt->execute([$_SESSION['client_id']]);
$bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Airstro | My Bookings</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="bookings-page">
  <div class="container">
    <div class="bookings-header">
      <div>
        <h1>My trips</h1>
        <p class="text-muted" style="font-size:12px;">
          View all your current and past bookings.
        </p>
      </div>
    </div>

    <?php if (empty($bookings)): ?>
      <div class="info-card">
        <div class="info-card-title">No bookings yet</div>
        <p class="text-muted" style="font-size:12px;">
          You haven't booked any flights yet. Start by searching for flights on the home page.
        </p>
        <div style="margin-top:8px;">
          <a href="home.php"><button class="btn btn-primary">Book a flight</button></a>
        </div>
      </div>
    <?php else: ?>
      <div class="table-card" style="padding:12px;">
        <?php foreach ($bookings as $b): ?>
          <div class="booking-card">
            <div>
              <div class="booking-route">
                <?php echo htmlspecialchars($b['from_code']); ?> → <?php echo htmlspecialchars($b['to_code']); ?>
              </div>
              <div class="booking-sub">
                <?php echo htmlspecialchars($b['from_city']); ?> to <?php echo htmlspecialchars($b['to_city']); ?>
              </div>
              <div class="booking-sub">
                PNR: <strong><?php echo htmlspecialchars($b['pnr']); ?></strong>
              </div>
            </div>

            <div>
              <div class="booking-sub">
                Departure:
                <strong>
                  <?php echo date('d M Y', strtotime($b['departure_datetime'])); ?>
                  · <?php echo date('H:i', strtotime($b['departure_datetime'])); ?>
                </strong>
              </div>
              <div class="booking-sub" style="margin-top:4px;">
                Status:
                <?php
                $statusClass = 'success';
                if ($b['booking_status'] === 'CANCELLED') $statusClass = 'danger';
                elseif ($b['booking_status'] === 'PENDING_PAYMENT') $statusClass = 'warning';
                ?>
                <span class="status-pill <?php echo $statusClass; ?>">
                  <?php echo htmlspecialchars($b['booking_status']); ?>
                </span>
              </div>
            </div>

            <div class="booking-actions">
              <a href="ticket.php?id=<?php echo $b['id']; ?>">
                <button class="btn btn-outline" type="button">View ticket</button>
              </a>

              <?php if ($b['booking_status'] === 'PENDING_PAYMENT'): ?>
                <a href="payment.php?booking_id=<?php echo $b['id']; ?>">
                  <button class="btn btn-primary" type="button">Pay now</button>
                </a>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  
</div>

<?php include 'footer.php'; ?>


</body>
</html>
