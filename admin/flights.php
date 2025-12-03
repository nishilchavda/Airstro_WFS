<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require '../config/db.php';

// ADD / UPDATE FLIGHT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id            = $_POST['id'] ?? '';
    $flight_no     = trim($_POST['flight_no']);
    $airline       = trim($_POST['airline']);
    $from_city     = trim($_POST['from_city']);
    $from_code     = trim($_POST['from_code']);
    $to_city       = trim($_POST['to_city']);
    $to_code       = trim($_POST['to_code']);
    $departure     = $_POST['departure'];
    $arrival       = $_POST['arrival'];
    $price         = (float)$_POST['price'];
    $total_seats   = (int)$_POST['total_seats'];
    $status        = $_POST['status'];

    if ($id) {
        // UPDATE
        $stmt = $pdo->prepare("UPDATE flights SET 
            flight_no=?, airline=?, from_city=?, from_code=?, 
            to_city=?, to_code=?, departure_datetime=?, arrival_datetime=?, 
            price=?, total_seats=?, seats_available=?, status=? 
            WHERE id=?");
        $stmt->execute([
            $flight_no, $airline, $from_city, $from_code,
            $to_city, $to_code, $departure, $arrival,
            $price, $total_seats, $total_seats, $status, $id
        ]);
    } else {
        // INSERT NEW
        $stmt = $pdo->prepare("INSERT INTO flights 
            (flight_no, airline, from_city, from_code, 
             to_city, to_code, departure_datetime, arrival_datetime, 
             price, total_seats, seats_available, status)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
            $flight_no, $airline, $from_city, $from_code,
            $to_city, $to_code, $departure, $arrival,
            $price, $total_seats, $total_seats, $status
        ]);
    }
    header("Location: flights.php");
    exit;
}

// CANCEL (soft delete)
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("UPDATE flights SET status = 'CANCELLED' WHERE id = ?")->execute([$id]);
    header("Location: flights.php");
    exit;
}


// EDIT FETCH
$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM flights WHERE id=?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
}

// FETCH ALL
$stmt = $pdo->query("SELECT * FROM flights ORDER BY departure_datetime ASC");
$flights = $stmt->fetchAll();

include 'header.php';
?>

<div class="container" style="padding: 20px 0 40px;">
    <div class="table-card">
        <h2><?php echo $edit ? "Edit Flight" : "Add New Flight"; ?></h2>

        <!-- FLIGHT FORM -->
        <form method="POST" style="display:grid; grid-template-columns:repeat(4,1fr); gap:10px; margin-bottom:18px;">
            <input type="hidden" name="id" value="<?php echo $edit['id'] ?? ''; ?>">

            <input class="form-input" type="text" name="flight_no" placeholder="Flight No" value="<?php echo $edit['flight_no'] ?? ''; ?>" required>
            <input class="form-input" type="text" name="airline" placeholder="Airline" value="<?php echo $edit['airline'] ?? ''; ?>" required>
            <input class="form-input" type="text" name="from_city" placeholder="From City" value="<?php echo $edit['from_city'] ?? ''; ?>" required>
            <input class="form-input" type="text" name="from_code" placeholder="From Code" value="<?php echo $edit['from_code'] ?? ''; ?>" required>
            <input class="form-input" type="text" name="to_city" placeholder="To City" value="<?php echo $edit['to_city'] ?? ''; ?>" required>
            <input class="form-input" type="text" name="to_code" placeholder="To Code" value="<?php echo $edit['to_code'] ?? ''; ?>" required>

            <input class="form-input" type="datetime-local" name="departure" value="<?php echo isset($edit['departure_datetime']) ? date('Y-m-d\TH:i', strtotime($edit['departure_datetime'])) : ''; ?>" required>
            <input class="form-input" type="datetime-local" name="arrival" value="<?php echo isset($edit['arrival_datetime']) ? date('Y-m-d\TH:i', strtotime($edit['arrival_datetime'])) : ''; ?>" required>

            <input class="form-input" type="number" step="0.01" name="price" placeholder="Ticket Price" value="<?php echo $edit['price'] ?? ''; ?>" required>
            <input class="form-input" type="number" name="total_seats" placeholder="Total Seats" value="<?php echo $edit['total_seats'] ?? ''; ?>" required>

            <select class="form-input" name="status">
                <option value="SCHEDULED" <?php echo (isset($edit['status']) && $edit['status'] == "SCHEDULED") ? "selected" : ""; ?>>Scheduled</option>
                <option value="CANCELLED" <?php echo (isset($edit['status']) && $edit['status'] == "CANCELLED") ? "selected" : ""; ?>>Cancelled</option>
            </select>

            <button class="btn btn-primary" type="submit" style="grid-column: span 4; justify-content:center;">
                <?php echo $edit ? "Update Flight" : "Add Flight"; ?>
            </button>
        </form>

        <!-- FLIGHT TABLE -->
        <table class="table">
            <thead>
                <tr>
                    <th>Flight</th>
                    <th>Route</th>
                    <th>Date & Time</th>
                    <th>Price</th>
                    <th>Seats</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($flights as $f): ?>
                <tr>
                    <td>
                        <?php echo $f['flight_no']; ?><br>
                        <small class="text-muted"><?php echo $f['airline']; ?></small>
                    </td>
                    <td><?php echo $f['from_code']; ?> → <?php echo $f['to_code']; ?><br>
                        <small class="text-muted"><?php echo $f['from_city']; ?> to <?php echo $f['to_city']; ?></small>
                    </td>
                    <td><?php echo date("d M Y H:i", strtotime($f['departure_datetime'])); ?></td>
                    <td>₹<?php echo number_format($f['price']); ?></td>
                    <td><?php echo $f['seats_available']; ?>/<?php echo $f['total_seats']; ?></td>
                    <td><?php echo $f['status']; ?></td>
                    <td>
                        <a href="flights.php?edit=<?php echo $f['id']; ?>"><button class="btn btn-outline" type="button">Edit</button></a>
                        <a href="flights.php?delete=<?php echo $f['id']; ?>" onclick="return confirm('Delete this flight?');">
                            <button class="btn btn-outline" type="button">Delete</button>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>

<?php include 'footer.php'; ?>
