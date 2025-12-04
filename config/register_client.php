<?php
// config/register_client.php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../client/register.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($name === '' || $email === '' || $password === '') {
    $_SESSION['error'] = 'All fields are required.';
    header('Location: ../client/register.php');
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = 'Email already registered.';
        header('Location: ../client/register.php');
        exit;
    }

    $hash = $password;
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?,?,?)');
    $stmt->execute([$name, $email, $hash]);

    $_SESSION['success'] = 'Account created. Please log in.';
    header('Location: ../client/login.php');
    exit;
} catch (PDOException $e) {
    $_SESSION['error'] = 'Something went wrong.';
    header('Location: ../client/register.php');
    exit;
}
?>