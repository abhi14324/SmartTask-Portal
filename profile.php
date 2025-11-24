<?php
session_start();
require "config.php";

// Block admin from using user profile
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$page_title = "My Profile - SmartTask Portal";

ob_start();
?>

<h2 style="margin-bottom:20px;">My Profile</h2>

<div class="card" style="padding:25px;display:flex;flex-wrap:wrap;gap:25px;align-items:flex-start;">

    <!-- Profile Image -->
    <div>
        <img src="uploads/<?= $user['profile_image'] ?>"
             style="width:140px;height:140px;border-radius:12px;object-fit:cover;
                    border:3px solid #2f73d9;">
    </div>

    <!-- User Details -->
    <div style="flex:1;min-width:260px;">
        <h3 style="color:#2f73d9;"><?= $user['full_name'] ?></h3>

        <p><strong>Email:</strong> <?= $user['email'] ?></p>
        <p><strong>Phone:</strong> <?= $user['phone'] ?></p>
        <p><strong>Education:</strong> <?= $user['education_level'] ?></p>
        <p><strong>Date of Birth:</strong> <?= $user['birthdate'] ?></p>
        <p><strong>Address:</strong> <?= $user['address'] ?></p>

        <div style="margin-top:20px;">
            <a href="edit_profile.php">
                <button class="btn btn-primary" style="margin-right:10px;">Edit Profile</button>
            </a>

            <a href="change_password.php">
                <button class="btn btn-primary">Change Password</button>
            </a>
        </div>
    </div>

</div>

<!-- Activity Summary -->
<div class="card" style="margin-top:25px;">
    <h3 style="margin-bottom:15px;">Account Overview</h3>

    <table>
        <tr>
            <th>Field</th>
            <th>Status</th>
        </tr>

        <tr>
            <td>Profile Picture</td>
            <td><?= $user['profile_image'] ? "Uploaded" : "Not Uploaded" ?></td>
        </tr>

        <tr>
            <td>Email Verified</td>
            <td style="color:#27ae60;">✔ Verified</td>
        </tr>

        <tr>
            <td>Account Role</td>
            <td>User</td>
        </tr>
    </table>
</div>

<?php
$content = ob_get_clean();
include "layout.php";
?>
