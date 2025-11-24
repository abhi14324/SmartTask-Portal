<?php
session_start();
require "config.php";

// Allow only admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

/* ---------------- STATS ---------------- */

// Total users
$total_users = $pdo->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetchColumn();

// Total tasks
$total_tasks = $pdo->query("SELECT COUNT(*) FROM todos")->fetchColumn();

// Total activity logs
$total_logs = $pdo->query("SELECT COUNT(*) FROM activity_logs")->fetchColumn();

/* ---------------- RECENT DATA ---------------- */

// Last 5 users
$users_stmt = $pdo->query("
    SELECT full_name, email, created_at FROM users
    WHERE role='user' ORDER BY id DESC LIMIT 5
");
$recent_users = $users_stmt->fetchAll();

// Last 5 tasks
$tasks_stmt = $pdo->query("
    SELECT task, status, created_at FROM todos
    ORDER BY id DESC LIMIT 5
");
$recent_tasks = $tasks_stmt->fetchAll();

$page_title = "Admin Dashboard - SmartTask Portal";

ob_start();
?>

<h2 style="margin-bottom:22px;">Admin Dashboard</h2>

<!-- STAT CARDS -->
<div style="display:flex;flex-wrap:wrap;gap:20px;">

    <div class="card" style="flex:1;min-width:220px;">
        <h3 style="color:#2f73d9;">Total Users</h3>
        <p style="font-size:30px;margin-top:10px;"><?= $total_users ?></p>
    </div>

    <div class="card" style="flex:1;min-width:220px;">
        <h3 style="color:#27ae60;">Total Tasks</h3>
        <p style="font-size:30px;margin-top:10px;"><?= $total_tasks ?></p>
    </div>

    <div class="card" style="flex:1;min-width:220px;">
        <h3 style="color:#8e44ad;">Activity Logs</h3>
        <p style="font-size:30px;margin-top:10px;"><?= $total_logs ?></p>
    </div>

</div>

<!-- USERS TABLE -->
<div class="card" style="margin-top:25px;">
    <h3 style="margin-bottom:12px;">Recent Users</h3>

    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Registered On</th>
        </tr>

        <?php foreach ($recent_users as $u): ?>
            <tr>
                <td><?= $u['full_name'] ?></td>
                <td><?= $u['email'] ?></td>
                <td><?= date("d M Y", strtotime($u['created_at'])) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<!-- TASKS TABLE -->
<div class="card" style="margin-top:25px;">
    <h3 style="margin-bottom:12px;">Recent Tasks</h3>

    <table>
        <tr>
            <th>Task</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>

        <?php foreach ($recent_tasks as $t): ?>
            <tr>
                <td><?= $t['task'] ?></td>
                <td>
                    <span style="
                        padding:3px 8px;
                        border-radius:6px;
                        font-size:13px;
                        background:
                            <?= $t['status']=='completed'?'#27ae60':'#f4d03f' ?>;
                        color:white;">
                        <?= ucfirst($t['status']) ?>
                    </span>
                </td>
                <td><?= date("d M Y", strtotime($t['created_at'])) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<!-- CHART SECTION -->
<div class="card" style="margin-top:25px;">
    <h3>Task Summary Chart</h3>
    <canvas id="taskChart" style="margin-top:25px;"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('taskChart'), {
    type: 'bar',
    data: {
        labels: ["Users", "Tasks", "Activity Logs"],
        datasets: [{
            label: "Admin Overview",
            data: [<?= $total_users ?>, <?= $total_tasks ?>, <?= $total_logs ?>],
            backgroundColor: ["#2f73d9", "#27ae60", "#8e44ad"]
        }]
    }
});
</script>

<?php
$content = ob_get_clean();
include "layout.php";
?>
