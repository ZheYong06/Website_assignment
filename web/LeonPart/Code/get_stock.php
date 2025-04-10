<?php
header('Content-Type: application/json');

// 数据库连接
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crud";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

$product_name = $_GET['product'];
$size = isset($_GET['size']) ? $_GET['size'] : 'S';

// 查询特定产品和尺寸的库存
$sql = "SELECT quantity AS stock FROM item WHERE name = ? AND size = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $product_name, $size);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['stock' => $row['stock']]);
} else {
    echo json_encode(['stock' => 5]); // 默认库存
}

$stmt->close();
$conn->close();
?>