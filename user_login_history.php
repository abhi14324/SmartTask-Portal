<?php
session_start();
require "config.php";

// Only admin allowed
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch login activity joined with user table
$stmt = $pdo->query("
    SELECT user_login_activity.*, users.full_name, users.email
    FROM user_login_activity
    JOIN users ON user_login_activity.user_id = users.id
    ORDER BY user_login_activity.id DESC
");

$logs = $stmt->fetchAll();

$page_title = "User Login Activity - SmartTask Portal";

ob_start();
?>

<h2 style="margin-bottom:20px;">User Login Activity</h2>

<!-- SEARCH BAR -->
<div class="card" style="padding:15px;margin-bottom:20px;">
    <input
        type="text"
        id="searchLog"
        placeholder="Search by username or email..."
        style="padding:12px;font-size:15px;width:100%;border:1px solid #d7dde5;border-radius:8px;"
    >
</div>

<div class="card">

    <table id="logTable">
        <tr>
            <th>User</th>
            <th>Email</th>
            <th>Login Time</th>
        </tr>

        <?php foreach ($logs as $log): ?>
        <tr>
            <td><?= $log['full_name'] ?></td>

            <td><?= $log['email'] ?></td>

            <td>
                <?= date("d M Y, h:i A", strtotime($log['login_time'])) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

</div>

<script>
// Live Search (Username + Email)
document.getElementById("searchLog").addEventListener("keyup", function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#logTable tr:not(:first-child)");

    rows.forEach(row => {
        let name = row.cells[0].innerText.toLowerCase();
        let email = row.cells[1].innerText.toLowerCase();

        if (name.includes(filter) || email.includes(filter)) {
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
