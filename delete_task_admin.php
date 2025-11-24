<?php
session_start();
require "config.php";

// Only admin
if (!isset($_SESSION['admin_id'])) {
    die("Unauthorized access.");
}

$id = $_GET['id'];

$pdo->prepare("DELETE FROM todos WHERE id=?")->execute([$id]);

header("Location: manage_tasks.php");
exit();
?>
