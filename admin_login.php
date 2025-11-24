<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
require 'config.php';

// If already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check from admin table
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE email=? LIMIT 1");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && md5($password) === $admin['password']) {
        $_SESSION['admin_id'] = $admin['id'];
        header("Location: admin_dashboard.php");
        exit();
    }
    else {
        $message = "Invalid admin email or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - SmartTask Portal</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>

<div class="auth-container">
    <h2 class="title">Admin Panel Login</h2>

    <?php if ($message): ?>
        <p class="error"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST" class="auth-form">

        <input type="email" name="email" placeholder="Admin Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" class="neon-btn">Login</button>

        <p class="switch-text">
            Back to user login? <a href="login.php">Click Here</a>
        </p>

    </form>
</div>

</body>
</html>
