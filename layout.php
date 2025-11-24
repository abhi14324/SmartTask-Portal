<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?? "SmartTask Portal" ?></title>

    <!-- Filled Icons (Material Symbols) -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet" />

    <!-- Corporate Global CSS -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        .material-symbols-rounded {
            font-variation-settings:
                'FILL' 1,
                'wght' 400,
                'GRAD' 0,
                'opsz' 48;
            font-size: 22px;
        }
    </style>
</head>

<body>

<!-- MOBILE MENU BUTTON -->
<div class="menu-btn" onclick="toggleSidebar()">
    <span class="material-symbols-rounded">menu</span>
</div>

<div class="page-container">

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">

        <h2>SmartTask Portal</h2>

        <?php if (!empty($_SESSION['admin_id'])): ?>

            <!-- ADMIN MENU -->
            <a href="admin_dashboard.php">
                <span class="material-symbols-rounded">dashboard</span> Admin Dashboard
            </a>

            <a href="manage_users.php">
                <span class="material-symbols-rounded">group</span> Manage Users
            </a>

            <a href="manage_tasks.php">
                <span class="material-symbols-rounded">checklist</span> Manage Tasks
            </a>

            <a href="user_login_history.php">
                <span class="material-symbols-rounded">history</span> Login Activity
            </a>

            <a href="admin_logout.php" style="color:#ffeded;">
                <span class="material-symbols-rounded">logout</span> Logout
            </a>

        <?php else: ?>

            <!-- USER MENU -->
            <a href="dashboard.php">
                <span class="material-symbols-rounded">home</span> Dashboard
            </a>

            <a href="profile.php">
                <span class="material-symbols-rounded">account_circle</span> Profile
            </a>

            <a href="todo.php">
                <span class="material-symbols-rounded">task_alt</span> Tasks
            </a>

            <a href="timeline.php">
                <span class="material-symbols-rounded">activity_zone</span> Activity
            </a>

            <a href="logout.php" style="color:#ffeded;">
                <span class="material-symbols-rounded">logout</span> Logout
            </a>

        <?php endif; ?>

    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <?= $content ?? "<h3>Page Loaded</h3>" ?>
    </div>

</div>

<script>
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("open");
}
</script>

</body>
</html>
