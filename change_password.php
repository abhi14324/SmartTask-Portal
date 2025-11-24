<?php
session_start();
require "config.php";

// Block admin
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

// Block visitors
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    // Check old password
    if (!password_verify($old, $user['password'])) {
        $error = "Old password is incorrect!";
    }
    else if ($new !== $confirm) {
        $error = "New password and confirm password do not match!";
    }
    else if (strlen($new) < 6) {
        $error = "New password must be at least 6 characters!";
    }
    else {
        // Update password
        $hashed = password_hash($new, PASSWORD_DEFAULT);

        $update = $pdo->prepare("UPDATE users SET password=? WHERE id=?");
        $update->execute([$hashed, $user_id]);

        $success = "Password updated successfully!";
    }
}

$page_title = "Change Password - SmartTask Portal";

ob_start();
?>

<h2 style="margin-bottom:20px;">Change Password</h2>

<div class="card" style="max-width:600px;">

    <?php if ($error): ?>
        <p style="background:#f8d7da;color:#721c24;padding:10px;border-radius:8px;">
            <?= $error ?>
        </p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="background:#d4edda;color:#155724;padding:10px;border-radius:8px;">
            <?= $success ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <label>Old Password</label>
        <input type="password" name="old_password" required>

        <label>New Password</label>
        <input type="password" name="new_password" required>

        <label>Confirm New Password</label>
        <input type="password" name="confirm_password" required>

        <button type="submit" class="btn btn-primary" style="margin-top:5px;">Update Password</button>

    </form>

</div>

<?php
$content = ob_get_clean();
include "layout.php";
?>
