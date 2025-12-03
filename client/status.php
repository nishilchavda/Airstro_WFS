<?php
// client/status.php
session_start();

$mode = $_POST['mode'] ?? 'pnr';
$statusResult = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // require '../config/db.php';

    if ($mode === 'pnr') {
        $pnr = trim($_POST['pnr'] ?? '');
        if ($pnr === '') {
            $error = 'Please enter a valid PNR.';
        } else {
            // TODO: Query DB using $pnr to fetch status

            // Demo result:
            $statusResult = [
                'flight_no' => 'AS 402',
                'from_code' => 'BOM',
                'to_code' => 'DEL',
                'from_city' => 'Mumbai',
                'to_city' => 'Delhi',
                'departure_date' => '2025-12-18',
                'departure_time' => '09:45',
                'arrival_time' => '11:55',
                'status' => 'ON TIME',
                'terminal' => 'T2',
                'gate' => 'A12'
            ];
        }
    } else {
        $from = trim($_POST['from'] ?? '');
        $to = trim($_POST['to'] ?? '');
        $date = $_POST['date'] ?? null;

        if ($from === '' || $to === '' || !$date) {
            $error = 'Please fill all fields to check flight status.';
        } else {
            // TODO: Query DB using route + date

            // Demo result:
            $statusResult = [
                'flight_no' => 'AS 210',
                'from_code' => strtoupper($from),
                'to_code' => strtoupper($to),
                'from_city' => 'Origin',
                'to_city' => 'Destination',
                'departure_date' => $date,
                'departure_time' => '14:05',
                'arrival_time' => '16:25',
                'status' => 'DELAYED',
                'terminal' => 'T1',
                'gate' => 'B04'
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Airstro | Flight Status</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="status-page">
  <div class="container status-grid">
    <!-- Left: Search Form -->
    <div class="status-card">
      <div class="status-title">Check flight status</div>
      <p class="status-subtitle">
        Track your flight using PNR or by selecting route and date.
      </p>

      <form class="status-form" action="status.php" method="POST">
        <input type="hidden" name="mode" id="mode_field" value="<?php echo htmlspecialchars($mode); ?>">

        <div class="status-toggle">
          <button type="button"
                  class="<?php echo $mode === 'pnr' ? 'active' : ''; ?>"
                  onclick="switchMode('pnr')">
            By PNR
          </button>
          <button type="button"
                  class="<?php echo $mode === 'route' ? 'active' : ''; ?>"
                  onclick="switchMode('route')">
            By Route & Date
          </button>
        </div>

        <div id="form_pnr" style="<?php echo $mode === 'pnr' ? '' : 'display:none;'; ?>">
          <div class="form-field">
            <label class="form-label" for="pnr">PNR</label>
            <input class="form-input" type="text" name="pnr" id="pnr"
                   placeholder="Enter your PNR code">
          </div>
        </div>

        <div id="form_route" style="<?php echo $mode === 'route' ? '' : 'display:none;'; ?>">
          <div class="form-field">
            <label class="form-label" for="from_city">From</label>
            <input class="form-input" type="text" name="from" id="from_city"
                   placeholder="e.g. BOM" value="<?php echo htmlspecialchars($_POST['from'] ?? ''); ?>">
          </div>
          <div class="form-field">
            <label class="form-label" for="to_city">To</label>
            <input class="form-input" type="text" name="to" id="to_city"
                   placeholder="e.g. DEL" value="<?php echo htmlspecialchars($_POST['to'] ?? ''); ?>">
          </div>
          <div class="form-field">
            <label class="form-label" for="date">Date</label>
            <input class="form-input" type="date" name="date" id="date"
                   value="<?php echo htmlspecialchars($_POST['date'] ?? ''); ?>">
          </div>
        </div>

        <?php if ($error): ?>
          <p style="color:#b91c1c; font-size:12px; margin-top:6px;">
            <?php echo htmlspecialchars($error); ?>
          </p>
        <?php endif; ?>

        <button class="btn btn-primary"
                type="submit"
                style="margin-top: 10px; width:100%; justify-content:center;">
          Check status
        </button>
      </form>
    </div>

    <!-- Right: Result -->
    <div>
      <?php if ($statusResult): ?>
        <div class="status-result-card">
          <div class="status-result-header">
            <div>
              <div class="status-flight-code">
                <?php echo htmlspecialchars($statusResult['flight_no']); ?>
              </div>
              <div class="status-route">
                <?php echo htmlspecialchars($statusResult['from_code']); ?>
                &nbsp;→&nbsp;
                <?php echo htmlspecialchars($statusResult['to_code']); ?>
              </div>
            </div>
            <div>
              <?php
              $statusClass = 'success';
              if (stripos($statusResult['status'], 'DELAY') !== false) $statusClass = 'warning';
              ?>
              <span class="status-pill <?php echo $statusClass; ?>">
                <?php echo htmlspecialchars($statusResult['status']); ?>
              </span>
            </div>
          </div>

          <div class="status-times">
            <div>
              <div class="ticket-meta-label">Departure</div>
              <div class="ticket-meta-value">
                <?php echo date('d M Y', strtotime($statusResult['departure_date'])); ?>
                · <?php echo htmlspecialchars($statusResult['departure_time']); ?>
              </div>
            </div>
            <div>
              <div class="ticket-meta-label">Arrival</div>
              <div class="ticket-meta-value">
                <?php echo htmlspecialchars($statusResult['arrival_time']); ?>
              </div>
            </div>
          </div>

          <div style="margin-top:10px; font-size:12px;">
            <div class="info-row">
              <span class="info-row-label">Terminal</span>
              <span class="info-row-value">
                <?php echo htmlspecialchars($statusResult['terminal']); ?>
              </span>
            </div>
            <div class="info-row">
              <span class="info-row-label">Gate</span>
              <span class="info-row-value">
                <?php echo htmlspecialchars($statusResult['gate']); ?>
              </span>
            </div>
          </div>
        </div>
      <?php else: ?>
        <div class="info-card">
          <div class="info-card-title">No flight selected</div>
          <p class="text-muted" style="font-size:12px;">
            Enter your PNR or choose a route and date to see live flight status here.
          </p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

<script>
  function switchMode(mode) {
    document.getElementById('mode_field').value = mode;
    document.getElementById('form_pnr').style.display = mode === 'pnr' ? '' : 'none';
    document.getElementById('form_route').style.display = mode === 'route' ? '' : 'none';

    const buttons = document.querySelectorAll('.status-toggle button');
    buttons.forEach(btn => btn.classList.remove('active'));
    if (mode === 'pnr') {
      buttons[0].classList.add('active');
    } else {
      buttons[1].classList.add('active');
    }
  }
</script>

</body>
</html>
