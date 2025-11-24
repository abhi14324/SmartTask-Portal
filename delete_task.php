<?php
session_start();
require "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM todos WHERE id=? AND user_id=?");
$stmt->execute([$id, $_SESSION['user_id']]);

header("Location: todo.php");
exit();
?>
