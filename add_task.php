<?php
session_start();
require "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST['task']);
    $desc = trim($_POST['description']);

    $stmt = $pdo->prepare("
        INSERT INTO todos (user_id, task, description, status)
        VALUES (?, ?, ?, 'pending')
    ");

    $stmt->execute([$user_id, $title, $desc]);

    header("Location: todo.php");
    exit();
}

$page_title = "Add Task";

ob_start();
?>

<h2 style="margin-bottom:20px;">Add New Task</h2>

<div class="card" style="max-width:600px;">

    <form method="POST">

        <label>Task Title</label>
        <input type="text" name="task" required>

        <label>Description</label>
        <textarea name="description" rows="4"></textarea>

        <button class="btn btn-primary" type="submit">Add Task</button>
    </form>

</div>

<?php
$content = ob_get_clean();
include "layout.php";
?>
