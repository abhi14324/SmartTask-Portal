<?php
session_start();
require "config.php";

// Only admin can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all users
$users = $pdo->query("
    SELECT id, full_name, email, role, created_at
    FROM users ORDER BY id DESC
")->fetchAll();

$page_title = "Manage Users - SmartTask Portal";

ob_start();
?>

<h2 style="margin-bottom:20px;">Manage Users</h2>

<!-- SEARCH BAR -->
<div class="card" style="padding:15px;margin-bottom:20px;">
    <input
        type="text"
        id="searchInput"
        placeholder="Search user by name or email..."
        style="padding:12px;font-size:15px;width:100%;border:1px solid #d7dde5;border-radius:8px;"
    >
</div>

<div class="card">

    <table id="userTable">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Registered On</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['full_name'] ?></td>
            <td><?= $u['email'] ?></td>

            <td>
                <span style="
                    padding:4px 10px;
                    font-size:12px;
                    border-radius:6px;
                    color:white;
                    background: <?= $u['role']=='admin' ? '#8e44ad' : '#2f73d9' ?>;">
                    <?= ucfirst($u['role']) ?>
                </span>
            </td>

            <td><?= date("d M Y", strtotime($u['created_at'])) ?></td>

            <td>
                <a href="delete_user.php?id=<?= $u['id'] ?>"
                   onclick="return confirm('Are you sure you want to delete this user?');">
                    <button class="btn btn-danger">Delete</button>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>

</div>

<script>
// Live Search Function
document.getElementById("searchInput").addEventListener("keyup", function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#userTable tr:not(:first-child)");

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
