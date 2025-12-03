<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require '../config/db.php';

// CANCEL BOOKING
if (isset($_GET['cancel'])) {
    $booking_id = (int)$_GET['cancel'];

    // Fetch booking + passenger count + flight id
    $stmt = $pdo->prepare("SELECT passengers, flight_id, booking_status FROM bookings WHERE id = ?");
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch();

    if ($booking && $booking['booking_status'] !== 'CANCELLED') {
        $pdo->beginTransaction();

        // 1. Mark booking cancelled
        $stmt = $pdo->prepare("UPDATE bookings SET booking_status = 'CANCELLED' WHERE id = ?");
        $stmt->execute([$booking_id]);

        // 2. Add seats back
        $stmt = $pdo->prepare("UPDATE flights SET seats_available = seats_available + ? WHERE id = ?");
        $stmt->execute([$booking['passengers'], $booking['flight_id']]);

        $pdo->commit();
    }

    header("Location: bookings.php");
    exit;
}

// FETCH ALL BOOKINGS
$stmt = $pdo->query(
"SELECT b.*, u.name AS user_name, u.email AS user_email,
        f.flight_no, f.airline, f.from_code, f.to_code, f.departure_datetime
 FROM bookings b
 JOIN users u ON b.user_id = u.id
 JOIN flights f ON b.flight_id = f.id
 ORDER BY b.created_at DESC"
);
$bookings = $stmt->fetchAll();

include 'header.php';
?>

<div class="container" style="padding: 20px 0 40px;">
    <div class="table-card">
        <h2>All Bookings</h2>
        <p class="text-muted" style="font-size:12px;margin-bottom:14px;">
            All user flight bookings are listed below.
        </p>

        <table class="table">
            <thead>
                <tr>
                    <th>PNR</th>
                    <th>Flight</th>
                    <th>User</th>
                    <th>Route</th>
                    <th>Date & Time</th>
                    <th>Passengers</th>
                    <th>Amount</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b): ?>
                <tr>
                    <td><strong><?php echo $b['pnr']; ?></strong></td>

                    <td>
                        <?php echo $b['flight_no']; ?><br>
                        <span class="text-muted" style="font-size:11px;">
                            <?php echo $b['airline']; ?>
                        </span>
                    </td>

                    <td>
                        <?php echo $b['user_name']; ?><br>
                        <span class="text-muted" style="font-size:11px;">
                            <?php echo $b['user_email']; ?>
                        </span>
                    </td>

                    <td><?php echo $b['from_code']; ?> → <?php echo $b['to_code']; ?></td>

                    <td><?php echo date('d M Y H:i', strtotime($b['departure_datetime'])); ?></td>

                    <td><?php echo $b['passengers']; ?></td>

                    <td>₹<?php echo number_format($b['total_amount']); ?></td>

                    <td>
                        <?php
                        $paymentClass = ($b['payment_status'] === 'PAID') ? 'success' : 'warning';
                        ?>
                        <span class="status-pill <?php echo $paymentClass; ?>">
                            <?php echo $b['payment_status']; ?>
                        </span>
                    </td>

                    <td>
                        <?php
                        $bookingClass = 'success';
                        if ($b['booking_status'] === 'CANCELLED') $bookingClass = 'danger';
                        elseif ($b['booking_status'] === 'PENDING_PAYMENT') $bookingClass = 'warning';
                        ?>
                        <span class="status-pill <?php echo $bookingClass; ?>">
                            <?php echo $b['booking_status']; ?>
                        </span>
                    </td>

                    <td>
                        <?php if ($b['booking_status'] !== 'CANCELLED'): ?>
                            <a href="bookings.php?cancel=<?php echo $b['id']; ?>"
                               onclick="return confirm('Cancel this booking? Seats will be restored.');">
                                <button class="btn btn-outline" type="button">Cancel</button>
                            </a>
                        <?php else: ?>
                            <button class="btn btn-outline" disabled>Cancelled</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>

<?php include 'footer.php'; ?>
