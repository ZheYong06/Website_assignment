<?php
include '../lib/database.php';   
session_start();
var_dump($_SESSION);
$user_id = $_SESSION["user_id"];
$address_name = $_POST["address_name"];
$floor_unit = $_POST["floor_unit"];
$state = $_POST["state"];
$district = $_POST["district"];
$postcode = $_POST["postcode"];

$conn = new mysqli("localhost", "root", "", "online_shopping");

$stmt = $conn->prepare("INSERT INTO user_address (user_id, address_name, floor_unit, state, district, postcode) VALUES (?, ?, ?, ?, ?, ?)");

$stmt->bind_param("isssss", $user_id, $address_name, $floor_unit, $state, $district, $postcode);

$stmt->execute();
$stmt->close();
$conn->close();

