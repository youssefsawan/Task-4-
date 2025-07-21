<?php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $pass = trim($_POST['password']);

    if (empty($name) || empty($pass)) {
        header("location: login.php?error=Please fill in all fields");
        exit;
    }

    $sql = $con->prepare("SELECT * FROM users WHERE name = ?");
    $sql->bind_param("s", $name);
    $sql->execute();
    $result = $sql->get_result();
    $user = $result->fetch_assoc();

    if ($user && $pass === $user['password']) {
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'email' => $user['email'],
            'name'  => $user['name']
        ];
        header("location: index.php");
        exit;
    } else {
        header("location: login.php?error=Invalid name or password");
        exit;
    }
} else {
    header("location: login.php");
    exit;
}
