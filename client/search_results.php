<?php
session_start();
require '../config/db.php';

$from = trim($_GET['from'] ?? '');
$to = trim($_GET['to'] ?? '');
$departure = $_GET['departure'] ?? '';
$return_date = $_GET['return'] ?? '';
$trip = $_GET['trip'] ?? 'oneway';
$passengers = $_GET['passengers'] ?? 1;

$outbound_id = $_GET['outbound'] ?? null;
$select_return = isset($_GET['select_return']);

// Query flights
function fetchFlights($pdo, $from, $to, $date) {
    $query = 'SELECT * FROM flights WHERE status="SCHEDULED" AND seats_available > 0
              AND (from_city LIKE ? OR from_code LIKE ?)
              AND (to_city LIKE ? OR to_code LIKE ?)
              AND DATE(departure_datetime) = ?
              ORDER BY departure_datetime ASC';
    $stmt = $pdo->prepare($query);
    $stmt->execute(["%$from%", "%$from%", "%$to%", "%$to%", $date]);
    return $stmt->fetchAll();
}

// Logic for round-trip selection
if ($trip == 'round' && $select_return) {
    $flights = fetchFlights($pdo, $from, $to, $departure); // inbound direction reversed already in link
} else {
    $flights = fetchFlights($pdo, $from, $to, $departure);
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Airstro | Search Results</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="bookings-page"><div class="container">
  <div class="bookings-header">
    <div>
      <h1>
        <?php
        if ($trip == 'round' && !$select_return) echo "Select Departure Flight";
        elseif ($trip == 'round' && $select_return) echo "Select Return Flight";
        else echo "Available Flights";
        ?>
      </h1>
    </div>
  </div>

  <?php if (empty($flights)): ?>
    <div class="info-card"><div class="info-card-title">No flights found</div></div>
  <?php else: ?>
  <div class="table-card" style="padding:12px;">
  <?php foreach ($flights as $f): ?>
    <div class="booking-card">

      <div>
        <div class="booking-route"><?= $f['from_code'] ?> → <?= $f['to_code'] ?></div>
        <div class="booking-sub"><?= $f['from_city'] ?> to <?= $f['to_city'] ?></div>
        <div class="booking-sub">Flight <?= $f['flight_no'] ?> · <?= $f['airline'] ?></div>
      </div>

      <div>
        <div class="booking-sub">Departure: <strong><?= date("d M Y H:i", strtotime($f['departure_datetime'])) ?></strong></div>
        <div class="booking-sub">Arrival: <strong><?= date("d M Y H:i", strtotime($f['arrival_datetime'])) ?></strong></div>
        <div class="booking-sub">Seats left: <strong><?= $f['seats_available'] ?></strong></div>
      </div>

      <div class="booking-actions">
        <div class="booking-sub">From <strong>₹<?= number_format($f['price']) ?></strong></div>

        <?php if ($trip == 'round' && !$select_return): ?>
          <!-- Step 1 Round trip select outbound -->
          <a href="search_results.php?select_return=1&trip=round&from=<?= urlencode($to) ?>&to=<?= urlencode($from) ?>&departure=<?= $return_date ?>&passengers=<?= $passengers ?>&outbound=<?= $f['id'] ?>">
            <button class="btn btn-primary">Select</button>
          </a>
        <?php elseif ($trip == 'round' && $select_return): ?>
          <!-- Step 2 Round trip select inbound -->
          <a href="book.php?outbound_id=<?= $outbound_id ?>&return_id=<?= $f['id'] ?>&passengers=<?= $passengers ?>">
            <button class="btn btn-primary">Book Round Trip</button>
          </a>
        <?php else: ?>
          <!-- One way -->
          <a href="book.php?flight_id=<?= $f['id'] ?>&passengers=<?= $passengers ?>">
            <button class="btn btn-primary">Book</button>
          </a>
        <?php endif; ?>
      </div>

    </div>
  <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div></div>

<?php include 'footer.php'; ?>
</body>
</html>
