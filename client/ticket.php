<?php
// client/ticket.php
session_start();
if (!isset($_SESSION['client_id'])) {
    header('Location: login.php');
    exit;
}
require '../config/db.php';

$booking_id = (int)($_GET['id'] ?? 0);
if ($booking_id <= 0) {
    die('Invalid ticket.');
}

$stmt = $pdo->prepare(
  'SELECT b.*, f.flight_no, f.airline, f.from_city, f.from_code, f.to_city, f.to_code,
          f.departure_datetime, f.arrival_datetime
   FROM bookings b
   JOIN flights f ON b.flight_id = f.id
   WHERE b.id = ? AND b.user_id = ?'
);
$stmt->execute([$booking_id, $_SESSION['client_id']]);
$booking = $stmt->fetch();

if (!$booking) {
    die('Ticket not found.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Airstro | Ticket</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="ticket-page">
  <div class="container">
    <div style="margin-bottom: 12px;">
      <div class="tag">E-ticket</div>
      <h1 style="font-size: 20px; font-weight: 750; margin-top: 6px; color:#0f172a;">
        Booking <?php echo htmlspecialchars($booking['booking_status']); ?>
      </h1>
      <p class="text-muted" style="font-size:12px; margin-top:2px;">
        Your trip details and passenger information are given below.
      </p>
    </div>

    <div class="ticket-layout">
      <!-- Main Ticket Card -->
      <div class="ticket-card">
        <div class="ticket-header">
          <div>
            <div class="ticket-route">
              <?php echo htmlspecialchars($booking['from_code']); ?> → <?php echo htmlspecialchars($booking['to_code']); ?>
            </div>
            <div class="ticket-code">
              <?php echo htmlspecialchars($booking['from_city']); ?> to <?php echo htmlspecialchars($booking['to_city']); ?>
            </div>
          </div>

          <div style="text-align: right;">
            <span class="ticket-code">PNR</span><br>
            <span style="font-weight:700; font-size:13px;">
              <?php echo htmlspecialchars($booking['pnr']); ?>
            </span>
          </div>
        </div>

        <div class="ticket-meta-grid">
          <div>
            <div class="ticket-meta-label">Flight</div>
            <div class="ticket-meta-value">
              <?php echo htmlspecialchars($booking['flight_no']); ?>
            </div>
          </div>
          <div>
            <div class="ticket-meta-label">Date</div>
            <div class="ticket-meta-value">
              <?php echo date('d M Y', strtotime($booking['departure_datetime'])); ?>
            </div>
          </div>
          <div>
            <div class="ticket-meta-label">Status</div>
            <div class="ticket-meta-value">
              <span class="status-pill success">
                <?php echo htmlspecialchars($booking['booking_status']); ?>
              </span>
            </div>
          </div>
          <div>
            <div class="ticket-meta-label">Departure</div>
            <div class="ticket-meta-value">
              <?php echo date('H:i', strtotime($booking['departure_datetime'])); ?>
            </div>
          </div>
          <div>
            <div class="ticket-meta-label">Arrival</div>
            <div class="ticket-meta-value">
              <?php echo date('H:i', strtotime($booking['arrival_datetime'])); ?>
            </div>
          </div>
          <div>
            <div class="ticket-meta-label">Passengers</div>
            <div class="ticket-meta-value">
              <?php echo (int)$booking['passengers']; ?>
            </div>
          </div>
        </div>

        <div class="ticket-footer">
          <span class="text-muted">
            Please report at least 2 hours prior to departure for domestic flights.
          </span>
          <button class="btn btn-outline" onclick="window.print();">
            Download / Print ticket
          </button>
        </div>
      </div>

      <!-- Side Info Cards -->
      <div>
        <div class="info-card">
          <div class="info-card-title">Passenger</div>
          <div class="info-row">
            <span class="info-row-label">Name</span>
            <span class="info-row-value">
              <?php echo htmlspecialchars($_SESSION['client_name'] ?? 'Airstro Passenger'); ?>
            </span>
          </div>
          <div class="info-row">
            <span class="info-row-label">PNR</span>
            <span class="info-row-value">
              <?php echo htmlspecialchars($booking['pnr']); ?>
            </span>
          </div>
        </div>

        <div class="info-card">
          <div class="info-card-title">Fare summary</div>
          <div class="info-row">
            <span class="info-row-label">Total paid</span>
            <span class="info-row-value" style="font-weight:700;">
              ₹<?php echo number_format($booking['total_amount']); ?>
            </span>
          </div>
          <div class="info-row">
            <span class="info-row-label">Payment status</span>
            <span class="info-row-value">
              <?php echo htmlspecialchars($booking['payment_status']); ?>
            </span>
          </div>
        </div>

        <div class="info-card">
          <div class="info-card-title">Important information</div>
          <p class="text-muted" style="font-size:11px;">
            Carry a valid government-issued photo ID. Baggage allowance and other rules apply as per airline policy.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
