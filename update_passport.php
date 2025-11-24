<?php
session_start();
require 'config.php';

$user_id = $_SESSION['user_id'];

$old = $_POST['old_password'];
$new = $_POST['new_password'];
$confirm = $_POST['confirm_password'];

$stmt = $pdo->prepare("SELECT password FROM users WHERE id=?");
$stmt->execute([$user_id]);
$stored = $stmt->fetchColumn();

if(!password_verify($old, $stored)) {
    die("Old password incorrect!");
}

if($new !== $confirm) {
    die("New passwords do not match!");
}

$new_hashed = password_hash($new, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password=? WHERE id=?");
$stmt->execute([$new_hashed,$user_id]);

header("Location: profile.php");
?>
