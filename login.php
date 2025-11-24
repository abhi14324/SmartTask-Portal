<?php
session_start();
require 'config.php';

// If admin is already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

// If user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

        // Admin Login
        if ($user['role'] === "admin") {
            $_SESSION['admin_id'] = $user['id'];
            header("Location: admin_dashboard.php");
            exit();
        }

        // Normal User Login
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit();
    }
    else {
        $message = "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - SmartTask Portal</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>

<div class="auth-container">
    <h2 class="title">Welcome Back</h2>

    <?php if ($message): ?>
        <p class="error"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST" class="auth-form">

        <input type="email" name="email" placeholder="Email Address" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" class="neon-btn">Login</button>

        <p class="switch-text">
            Don't have an account? <a href="register.php">Register</a>
        </p>

        <!-- ADMIN LOGIN LINK -->
        <p class="switch-text" style="margin-top:10px;">
            Are you admin? <a href="admin_login.php">Admin Login</a>
        </p>

    </form>
</div>

</body>
</html>
