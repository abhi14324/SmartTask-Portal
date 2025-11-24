<?php
session_start();
require "config.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'];

// Prevent admin from deleting himself
if ($id == $_SESSION['admin_id']) {
    die("You cannot delete your own admin account.");
}

$pdo->prepare("DELETE FROM users WHERE id=?")->execute([$id]);

header("Location: manage_users.php");
exit();
?>
