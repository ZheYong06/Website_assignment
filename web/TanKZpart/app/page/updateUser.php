<?php
include '../lib/database.php';   

session_start();
// 确保 $_POST 中的键存在，防止 "Undefined index" 错误
$user_id = $_SESSION['user_id'];
$user_account = $_POST['user_account'];
$username = $_POST['username'];
$gender = $_POST["gender"] ?? "";
$date_of_birth = $_POST['date_of_birth'];

// 连接数据库
$conn = new mysqli("localhost", "root", "", "online_shopping");

// 检查连接
if ($conn->connect_error) {
    die("database failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT user_account_check,user_account_old FROM user_profile WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_account_check,$user_account_old);
$stmt->fetch();
$stmt->close();


if ($user_account_check == "0" && $user_account_old !==$user_account) {
    $stmt = $conn->prepare("UPDATE user_profile 
    SET user_account = ?, 
        username = ?, 
        gender = ?, 
        date_of_birth = ?, 
        user_account_check = '1'
    WHERE user_id = ?");
    $stmt->bind_param("ssssi", $user_account, $username, $gender, $date_of_birth, $user_id);
} else {
    $stmt = $conn->prepare("UPDATE user_profile 
    SET username = ?, 
        gender = ?, 
        date_of_birth = ?
    WHERE user_id = ?");
    $stmt->bind_param("sssi", $username, $gender, $date_of_birth, $user_id);
}

$stmt->execute();
$stmt->close();
$conn->close();
