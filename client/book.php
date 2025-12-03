<?php
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit;
}
require '../config/db.php';

$outbound_id = (int)($_GET['outbound_id'] ?? 0);
$return_id   = (int)($_GET['return_id'] ?? 0);
$flight_id   = (int)($_GET['flight_id'] ?? 0);
$passengers  = (int)($_GET['passengers'] ?? 1);
$round_trip  = ($outbound_id && $return_id);

if ($round_trip) {
    $stmt = $pdo->prepare("SELECT * FROM flights WHERE id IN (?, ?) AND status='SCHEDULED'");
    $stmt->execute([$outbound_id, $return_id]);
    $flights = $stmt->fetchAll();
    if (count($flights) !== 2) die("Unavailable");
    $out = $flights[0];
    $ret = $flights[1];
    $total = ($out['price'] + $ret['price']) * $passengers;
} else {
    $stmt = $pdo->prepare("SELECT * FROM flights WHERE id=? AND status='SCHEDULED'");
    $stmt->execute([$flight_id]);
    $out = $stmt->fetch();
    if (!$out) die("Unavailable");
    $total = $out['price'] * $passengers;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($passengers > $out['seats_available'] || ($round_trip && $passengers > $ret['seats_available'])) {
        $error = "Not enough seats available.";
    } else {
        $pnr = "AS" . strtoupper(substr(md5(uniqid()), 0, 8));

        $stmt = $pdo->prepare(
          "INSERT INTO bookings (user_id, flight_id, return_flight_id, pnr, passengers, total_amount)
           VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $_SESSION['client_id'],
            $out['id'],
            $round_trip ? $ret['id'] : null,
            $pnr,
            $passengers,
            $total
        ]);

        $booking_id = $pdo->lastInsertId();

        $pdo->prepare("UPDATE flights SET seats_available = seats_available - ? WHERE id=?")
            ->execute([$passengers, $out['id']]);

        if ($round_trip) {
            $pdo->prepare("UPDATE flights SET seats_available = seats_available - ? WHERE id=?")
                ->execute([$passengers, $ret['id']]);
        }

        header("Location: payment.php?booking_id=$booking_id");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Confirm Booking</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="bookings-page"><div class="container">
  <div class="table-card" style="max-width:520px;margin:0 auto;">
    <h2>Review and confirm</h2>

    <p class="text-muted" style="font-size:12px;">
      <?= $out['from_code'] ?> → <?= $out['to_code'] ?> (<?= $out['flight_no'] ?>)
      <?php if ($round_trip): ?><br>
      Return: <?= $ret['from_code'] ?> → <?= $ret['to_code'] ?> (<?= $ret['flight_no'] ?>)
      <?php endif; ?>
    </p>

    <form method="POST">
      <?php if ($error): ?>
        <p style="color:#b91c1c;font-size:12px;"><?= $error ?></p>
      <?php endif; ?>
      <p>Total payable: <strong>₹<?= number_format($total) ?></strong></p>
      <button class="btn btn-primary" type="submit" style="width:100%;justify-content:center;">
        Continue to Payment
      </button>
    </form>
  </div>
</div></div>
</body>
</html>
