<?php
session_start();
require "config.php";

// Block admin from user dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch task counts
$pending = $pdo->query("SELECT COUNT(*) FROM todos WHERE user_id=$user_id AND status='pending'")->fetchColumn();
$completed = $pdo->query("SELECT COUNT(*) FROM todos WHERE user_id=$user_id AND status='completed'")->fetchColumn();
$total = $pending + $completed;

// Recent tasks
$recent = $pdo->prepare("SELECT * FROM todos WHERE user_id=? ORDER BY id DESC LIMIT 5");
$recent->execute([$user_id]);
$recent_tasks = $recent->fetchAll();

// Weekly stats
function getWeeklyStats($pdo, $uid) {
    $days = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
    $stats = [];

    foreach ($days as $day) {
        $q = $pdo->prepare("
            SELECT COUNT(*) FROM todos
            WHERE user_id=? AND status='completed'
            AND DAYNAME(created_at)=?
        ");
        $q->execute([$uid, $day]);
        $stats[$day] = $q->fetchColumn();
    }

    return $stats;
}

$weekly = getWeeklyStats($pdo, $user_id);

$page_title = "Dashboard - SmartTask Portal";

ob_start();
?>

<h2 style="margin-bottom:20px;">Welcome back, <?= $user['full_name'] ?> 👋</h2>

<!-- ANALYTICS CARDS -->
<div style="display:flex;flex-wrap:wrap;gap:20px;">

    <div class="card" style="flex:1;min-width:220px;">
        <h3 style="color:#2f73d9;">Pending Tasks</h3>
        <p style="font-size:28px;margin-top:10px;"><?= $pending ?></p>
    </div>

    <div class="card" style="flex:1;min-width:220px;">
        <h3 style="color:#27ae60;">Completed Tasks</h3>
        <p style="font-size:28px;margin-top:10px;"><?= $completed ?></p>
    </div>

    <div class="card" style="flex:1;min-width:220px;">
        <h3 style="color:#8e44ad;">Total Tasks</h3>
        <p style="font-size:28px;margin-top:10px;"><?= $total ?></p>
    </div>

</div>

<!-- WEEKLY CHART -->
<div class="card" style="margin-top:25px;">
    <h3>Weekly Task Completion</h3>
    <canvas id="weeklyChart" style="margin-top:20px;"></canvas>
</div>

<!-- TASK SUMMARY CHART -->
<div class="card" style="margin-top:25px;">
    <h3>Task Summary</h3>
    <canvas id="taskChart" style="margin-top:20px;"></canvas>
</div>

<!-- RECENT ACTIVITY -->
<div class="card" style="margin-top:25px;">
    <h3>Recent Activity</h3>

    <?php if (count($recent_tasks) === 0): ?>
        <p style="color:#7f8c8d;margin-top:10px;">No recent activity.</p>
    <?php endif; ?>

    <?php foreach ($recent_tasks as $t): ?>
        <div style="padding:12px 0;border-bottom:1px solid #e5e5e5;">
            <strong><?= $t['task'] ?></strong><br>
            <span style="color:#7f8c8d;"><?= $t['created_at'] ?></span>
        </div>
    <?php endforeach; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Weekly Chart
new Chart(document.getElementById('weeklyChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode(array_keys($weekly)) ?>,
        datasets: [{
            label: "Completed Tasks",
            data: <?= json_encode(array_values($weekly)) ?>,
            borderColor: "#2f73d9",
            backgroundColor: "rgba(47,115,217,0.15)",
            borderWidth: 3,
            tension: 0.3
        }]
    }
});

// Summary Chart
new Chart(document.getElementById('taskChart'), {
    type: 'bar',
    data: {
        labels: ["Pending", "Completed"],
        datasets: [{
            label: "Tasks",
            data: [<?= $pending ?>, <?= $completed ?>],
            backgroundColor: ["#e74c3c", "#27ae60"]
        }]
    }
});
</script>

<?php
$content = ob_get_clean();
include "layout.php";
?>
