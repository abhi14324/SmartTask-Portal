<?php
session_start();
require "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$task_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM todos WHERE id=? AND user_id=?");
$stmt->execute([$task_id, $_SESSION['user_id']]);
$task = $stmt->fetch();

if (!$task) {
    die("Task not found.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST['task']);
    $desc = trim($_POST['description']);
    $status = $_POST['status'];

    $update = $pdo->prepare("
        UPDATE todos
        SET task=?, description=?, status=?
        WHERE id=? AND user_id=?
    ");

    $update->execute([$title, $desc, $status, $task_id, $_SESSION['user_id']]);

    header("Location: todo.php");
    exit();
}

$page_title = "Edit Task";

ob_start();
?>

<h2>Edit Task</h2>

<div class="card" style="max-width:600px;">

    <form method="POST">

        <label>Task Title</label>
        <input type="text" name="task" value="<?= $task['task'] ?>" required>

        <label>Description</label>
        <textarea name="description" rows="4"><?= $task['description'] ?></textarea>

        <label>Status</label>
        <select name="status">
            <option value="pending" <?= $task['status']=="pending"?"selected":"" ?>>Pending</option>
            <option value="completed" <?= $task['status']=="completed"?"selected":"" ?>>Completed</option>
        </select>

        <button class="btn btn-primary" type="submit">Update Task</button>
    </form>

</div>

<?php
$content = ob_get_clean();
include "layout.php";
?>
