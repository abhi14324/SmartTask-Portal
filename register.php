<?php
session_start();
require 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $full = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $dob = trim($_POST['birthdate']);
    $pass = $_POST['password'];
    $edu = trim($_POST['education_level']);
    $address = trim($_POST['address']);

    // file
    $file = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    $filename = time() . "_" . $file;
    move_uploaded_file($tmp, "uploads/" . $filename);

    // Check duplicate
    $stmt = $pdo->prepare("SELECT email FROM users WHERE email=? OR username=?");
    $stmt->execute([$email, $username]);

    if ($stmt->rowCount() > 0) {
        $message = "Email or Username already taken!";
    } else {
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        $insert = $pdo->prepare("
            INSERT INTO users(full_name, username, email, phone, birthdate, password,
                               education_level, address, profile_image, role)
            VALUES (?,?,?,?,?,?,?,?,?, 'user')
        ");

        $insert->execute([
            $full, $username, $email, $phone, $dob,
            $hash, $edu, $address, $filename
        ]);

        header("Location: login.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Register - SmartTask Portal</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>

<div class="auth-container">
    <h2 class="title">Smart Task Portal</h2>

    <?php if ($message): ?>
        <p class="error"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="auth-form">

        <input type="text" name="full_name" placeholder="Full Name" required>

        <input type="text" name="username" placeholder="Username" required>

        <input type="email" name="email" placeholder="Email Address" required>

        <input type="text" name="phone" placeholder="Phone" required>

        <label>Date of Birth</label>
        <input type="date" name="birthdate" required>

        <input type="password" name="password" placeholder="Password" required>

        <input type="text" name="address" placeholder="Address" required>

        <select name="education_level" required>
            <option value="">Select Education Level</option>
            <option value="12th">12th</option>
            <option value="Diploma">Diploma</option>
            <option value="Bachelor's Degree">Bachelor's Degree</option>
            <option value="Master's Degree">Master's Degree</option>
        </select>

        <label class="file-label">Upload Profile Image</label>
        <input type="file" name="image" required>

        <button type="submit" class="neon-btn">Register</button>

        <p class="switch-text">
            Already have an account? <a href="login.php">Login</a>
        </p>
    </form>
</div>

</body>
</html>
