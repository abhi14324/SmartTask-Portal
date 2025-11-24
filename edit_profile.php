<?php
session_start();
require "config.php";

// Block admin
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

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $full = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $edu = trim($_POST['education_level']);
    $address = trim($_POST['address']);
    $birthdate = trim($_POST['birthdate']);

    // Profile image update
    $imageName = $user['profile_image'];

    if (!empty($_FILES['image']['name'])) {

        $file = $_FILES['image']['name'];
        $tmp = $_FILES['image']['tmp_name'];

        $imageName = time() . "_" . $file;
        move_uploaded_file($tmp, "uploads/" . $imageName);
    }

    // Update query
    $update = $pdo->prepare("
        UPDATE users SET
            full_name=?, email=?, phone=?, education_level=?, address=?, birthdate=?, profile_image=?
        WHERE id=?
    ");

    $update->execute([$full, $email, $phone, $edu, $address, $birthdate, $imageName, $user_id]);

    $message = "Profile updated successfully!";
}

$page_title = "Edit Profile - SmartTask Portal";

ob_start();
?>

<h2 style="margin-bottom:20px;">Edit Profile</h2>

<div class="card" style="max-width:650px;">

    <?php if ($message): ?>
        <p style="background:#d4edda;color:#155724;padding:10px;border-radius:8px;">
            <?= $message ?>
        </p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <label>Full Name</label>
        <input type="text" name="full_name" value="<?= $user['full_name'] ?>" required>

        <label>Email Address</label>
        <input type="email" name="email" value="<?= $user['email'] ?>" required>

        <label>Phone Number</label>
        <input type="text" name="phone" value="<?= $user['phone'] ?>">

        <label>Date of Birth</label>
        <input type="date" name="birthdate" value="<?= $user['birthdate'] ?>">

        <label>Education Level</label>
        <select name="education_level">
            <option value="12th" <?= $user['education_level']=="12th" ? "selected":"" ?>>12th</option>
            <option value="Diploma" <?= $user['education_level']=="Diploma" ? "selected":"" ?>>Diploma</option>
            <option value="Bachelor's Degree" <?= $user['education_level']=="Bachelor's Degree" ? "selected":"" ?>>Bachelor's Degree</option>
            <option value="Master's Degree" <?= $user['education_level']=="Master's Degree" ? "selected":"" ?>>Master's Degree</option>
        </select>

        <label>Complete Address</label>
        <textarea name="address" rows="3"><?= $user['address'] ?></textarea>

        <label>Update Profile Image</label>
        <input type="file" name="image">

        <img src="uploads/<?= $user['profile_image'] ?>"
             style="width:120px;margin-top:10px;border-radius:10px;border:2px solid #2f73d9;">

        <br><br>

        <button type="submit" class="btn btn-primary" style="width:150px;">Save Changes</button>

    </form>

</div>

<?php
$content = ob_get_clean();
include "layout.php";
?>
