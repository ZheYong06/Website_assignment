<?php
include 'c:\xampp\htdocs\a\code_ass\web\app\lib\database.php';
session_start();

$address_id = $_POST["address_id"];
$address_name = $_POST["address_name"];
$floor_unit = $_POST["floor_unit"];
$state = $_POST["state"];
$district = $_POST["district"];
$postcode = $_POST["postcode"];

$conn = new mysqli("localhost", "root", "", "online_shopping");

$stmt = $conn->prepare("UPDATE user_address set address_name=?,floor_unit=?,state=?,district=?,postcode=? WHERE address_id=?");
echo $address_id,$address_name ,$floor_unit,$state,$postcode,$district;
$stmt->bind_param("ssssii", $address_name, $floor_unit, $state, $district, $postcode,$address_id);

$stmt->execute();
$stmt->close();
$conn->close();

header("Location: /a/code_ass/web/program/address.php");
exit; // 加上 exit() 确保脚本在发送头部后停止执行


?>