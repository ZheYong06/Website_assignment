<?php
include '../lib/database.php';   
session_start();
$user_id = $_SESSION["user_id"];
$phone_number = $_POST["phone_number"];

$conn = new mysqli("localhost", "root", "", "online_shopping");

if ($conn->connect_errno)
    die("database failed" . $conn->connect_errno);

$stmt = $conn->prepare("UPDATE user_profile set phone_number=? WHERE user_id=?");

$stmt->bind_param("si", $phone_number, $user_id);
$stmt->execute();
$stmt->close();
$conn->close();

?>

