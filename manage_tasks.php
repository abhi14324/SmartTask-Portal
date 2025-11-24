<?php
session_start();
require "config.php";

// Only admin can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all tasks with user names
$stmt = $pdo->query("
    SELECT todos.id, todos.task, todos.status, todos.created_at,
           users.full_name, users.email
    FROM todos
    JOIN users ON todos.user_id = users.id
    ORDER BY todos.id DESC
");

$tasks = $stmt->fetchAll();

$page_title = "Manage Tasks - SmartTask Portal";

ob_start();
?>

<h2 style="margin-bottom:20px;">Manage Tasks</h2>

<!-- SEARCH BAR -->
<div class="card" style="padding:15px;margin-bottom:20px;">
    <input
        type="text"
        id="searchTask"
        placeholder="Search tasks or users..."
        style="padding:12px;font-size:15px;width:100%;border:1px solid #d7dde5;border-radius:8px;"
    >
</div>

<div class="card">

    <table id="taskTable">
        <tr>
            <th>Task</th>
            <th>User</th>
            <th>Status</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($tasks as $t): ?>
        <tr>
            <td><?= $t['task'] ?></td>

            <td>
                <strong><?= $t['full_name'] ?></strong><br>
                <small style="color:#7f8c8d;"><?= $t['email'] ?></small>
            </td>

            <td>
                <span style="
                    padding:4px 10px;
                    border-radius:6px;
                    font-size:12px;
                    color:white;
                    background: <?= $t['status']=='completed' ? '#27ae60' : '#f4d03f' ?>;
                ">
                    <?= ucfirst($t['status']) ?>
                </span>
            </td>

            <td><?= date("d M Y", strtotime($t['created_at'])) ?></td>

            <td>
                <a href="delete_task_admin.php?id=<?= $t['id'] ?>"
                   onclick="return confirm('Delete this task?');">
                    <button class="btn btn-danger">Delete</button>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>

</div>

<script>
// LIVE SEARCH
document.getElementById("searchTask").addEventListener("keyup", function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#taskTable tr:not(:first-child)");

    rows.forEach(row => {
        let task = row.cells[0].innerText.toLowerCase();
        let name = row.cells[1].innerText.toLowerCase();

        if (task.includes(filter) || name.includes(filter)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});
</script>

<?php
$content = ob_get_clean();
include "layout.php";
?>
