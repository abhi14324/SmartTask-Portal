<?php
session_start();
require 'config.php';

if(!isset($_SESSION['user_id'])) exit();

$user_id = $_SESSION['user_id'];

$full_name = $_POST['full_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$birthdate = $_POST['birthdate'];
$education = $_POST['education_level'];

// If new image uploaded
$image_name = $old_image = "";

$stmt = $pdo->prepare("SELECT profile_image FROM users WHERE id=?");
$stmt->execute([$user_id]);
$old_image = $stmt->fetchColumn();

if(!empty($_FILES['profile_image']['name'])) {
    $image_name = time() . "_" . $_FILES['profile_image']['name'];
    move_uploaded_file($_FILES['profile_image']['tmp_name'], "uploads/" . $image_name);
} else {
    $image_name = $old_image;
}

$stmt = $pdo->prepare("UPDATE users SET full_name=?,email=?,phone=?,birthdate=?,education_level=?,profile_image=? WHERE id=?");
$stmt->execute([$full_name,$email,$phone,$birthdate,$education,$image_name,$user_id]);

header("Location: profile.php");
?>
