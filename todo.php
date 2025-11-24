<?php
session_start();
require "config.php";

// block admin
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch tasks
$stmt = $pdo->prepare("SELECT * FROM todos WHERE user_id=? ORDER BY id DESC");
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll();

$page_title = "My Tasks - SmartTask Portal";

ob_start();
?>

<h2 style="margin-bottom:20px;">My Tasks</h2>

<a href="add_task.php">
    <button class="btn btn-primary" style="margin-bottom:20px;">+ Add New Task</button>
</a>

<div class="card">

    <?php if (count($tasks) === 0): ?>
        <p style="color:#7f8c8d;">No tasks found. Create your first task!</p>
    <?php endif; ?>

    <?php foreach ($tasks as $t): ?>

        <div style="padding:18px;border-bottom:1px solid #e6e9ef;
                    display:flex;justify-content:space-between;align-items:center;
                    flex-wrap:wrap;">

            <div style="max-width:70%;">
                <strong style="font-size:17px;"><?= $t['task'] ?></strong><br>
                <span style="color:#7f8c8d;font-size:14px;">
                    <?= $t['description'] ?>
                </span><br>

                <span style="
                    display:inline-block;
                    margin-top:6px;
                    padding:4px 10px;
                    font-size:12px;
                    border-radius:6px;
                    background: <?= $t['status']=='pending' ? '#f4d03f' : '#27ae60' ?>;
                    color:white;
                ">
                    <?= ucfirst($t['status']) ?>
                </span>

            </div>

            <div style="margin-top:10px;">
                <a href="update_task.php?id=<?= $t['id'] ?>">
                    <button class="btn btn-primary" style="margin-right:8px;">Edit</button>
                </a>

                <a href="delete_task.php?id=<?= $t['id'] ?>" onclick="return confirm('Delete this task?');">
                    <button class="btn btn-danger">Delete</button>
                </a>
            </div>

        </div>

    <?php endforeach; ?>

</div>

<?php
$content = ob_get_clean();
include "layout.php";
?>
