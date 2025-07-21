<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        header("Location: register.php?error=Please fill in all fields.");
        exit;
    }
if (!isset($_POST['terms']) || $_POST['terms'] !== 'agree') {
    header("Location: register.php?error=You must agree to the terms.");
    exit;
}

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.php?error=Invalid email format.");
        exit;
    }

    if ($password !== $confirm_password) {
        header("Location: register.php?error=Passwords do not match.");
        exit;
    }

    $checkStmt = $con->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        header("Location: register.php?error=Email is already registered.");
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $insertStmt = $con->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $insertStmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($insertStmt->execute()) {
        header("Location: login.php?success=Account created successfully.");
        exit;
    } else {
        header("Location: register.php?error=Something went wrong. Please try again.");
        exit;
    }
}
?>
