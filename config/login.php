<?php
// config/login.php
session_start();
require 'db.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    $_SESSION['error'] = "Email and password are required.";
    header("Location: ../client/login.php");
    exit;
}

/* ---- 1) CHECK ADMIN ---- */
$stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$admin = $stmt->fetch();

if ($admin && $password === $admin['password']) {
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_name'] = $admin['name'];
    header("Location: ../admin/flights.php");
    exit;
}

/* ---- 2) CHECK USER ---- */
$stmt2 = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
$stmt2->execute([$email]);
$user = $stmt2->fetch();

if ($user && $password === $user['password']) {
    $_SESSION['client_id'] = $user['id'];
    $_SESSION['client_name'] = $user['name'];
    $_SESSION['client_email'] = $user['email'];
    header("Location: ../client/home.php");
    exit;
}

/* ---- INVALID ---- */
$_SESSION['error'] = "Invalid email or password.";
header("Location: ../client/login.php");
exit;
