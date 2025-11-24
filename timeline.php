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

// Fetch logs from correct table name
$stmt = $pdo->prepare("SELECT * FROM activity_logs WHERE user_id=? ORDER BY id DESC LIMIT 100");
$stmt->execute([$user_id]);
$logs = $stmt->fetchAll();

$page_title = "Activity Timeline - SmartTask Portal";

ob_start();
?>

<h2 style="margin-bottom:20px;">Activity Timeline</h2>

<div class="card">

    <?php if (count($logs) === 0): ?>
        <p style="color:#7f8c8d;">No activity recorded yet.</p>
    <?php endif; ?>

    <?php foreach ($logs as $log): ?>

        <div style="padding:15px 0;border-bottom:1px solid #e6e9ef;">

            <div style="font-size:16px;font-weight:600;color:#2c3e50;">
                <?= $log['activity'] ?>
            </div>

            <div style="font-size:13px;color:#7f8c8d;margin-top:3px;">
                <?= date("d M Y, h:i A", strtotime($log['created_at'])) ?>
            </div>

        </div>

    <?php endforeach; ?>

</div>

<?php
$content = ob_get_clean();
include "layout.php";
?>
