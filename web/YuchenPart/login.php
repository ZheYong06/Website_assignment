<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_test_db";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}

// 启用 session
session_start();

// 存储 OTP 的临时数组（建议用数据库或更安全的 session 管理）
$stored_otp = []; // 模拟存储

// 处理请求
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === '' || $action === 'login') {
        // 原有登录逻辑
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            echo "Username or password missing";
            exit;
        }

        $stmt = $conn->prepare("SELECT admin_password FROM admin WHERE admin_name = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['admin_password'] === $password) {
                $_SESSION['logged_in_user'] = $username;
                echo "success";
            } else {
                echo "Incorrect password";
            }
        } else {
            echo "Incorrect username";
        }
        $stmt->close();
    } elseif ($action === 'send_otp') {
        // 发送 OTP 逻辑
        $email = $_POST['to_email'] ?? '';
        $username = $_SESSION['logged_in_user'] ?? ''; // 假设已登录

        if (empty($email) || empty($username)) {
            echo json_encode(["success" => false, "message" => "Email or username missing"]);
            exit;
        }

        $otp = rand(1000, 9999); // 生成 4 位 OTP
        $otp_key = $email . '_' . $username;
        $stored_otp[$otp_key] = $otp; // 存储 OTP

        // 返回 OTP（前端用 EmailJS 发送）
        echo json_encode(["success" => true, "otp" => $otp]);
        exit;
    } elseif ($action === 'verify_otp_and_update') {
        // 验证 OTP 并更新密码
        $email = $_POST['to_email'] ?? '';
        $otp = $_POST['otp'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $username = $_SESSION['logged_in_user'] ?? ''; // 假设已登录

        if (empty($email) || empty($otp) || empty($new_password) || empty($confirm_password) || empty($username)) {
            echo "All fields are required";
            exit;
        }

        if ($new_password !== $confirm_password) {
            echo "Passwords do not match";
            exit;
        }

        $otp_key = $email . '_' . $username;
        if (isset($stored_otp[$otp_key]) && $stored_otp[$otp_key] === $otp) {
            // OTP 验证通过，更新密码
            $stmt = $conn->prepare("UPDATE admin SET admin_password = ? WHERE admin_name = ?");
            $stmt->bind_param("ss", $new_password, $username);
            if ($stmt->execute()) {
                unset($stored_otp[$otp_key]); // 清除已用 OTP
                echo "Password updated successfully";
            } else {
                echo "Failed to update password";
            }
            $stmt->close();
        } else {
            echo "Invalid OTP";
        }
    } elseif ($action === 'reset_password') {
        // 修改后的密码重置逻辑，使用前端传递的 username
        $username = $_POST['username'] ?? '';
        $new_password = $_POST['new_password'] ?? '';

        if (empty($username) || empty($new_password)) {
            echo "所有字段都必须填写";
            exit;
        }

        // 验证用户名是否存在
        $stmt = $conn->prepare("SELECT admin_name FROM admin WHERE admin_name = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // 直接更新新密码
            $stmt = $conn->prepare("UPDATE admin SET admin_password = ? WHERE admin_name = ?");
            $stmt->bind_param("ss", $new_password, $username);
            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "密码更新失败";
            }
        } else {
            echo "用户名不存在";
        }
        $stmt->close();
    } elseif ($action === 'check_session') {
        // 检查 Session 状态
        if (isset($_SESSION['logged_in_user']) && !empty($_SESSION['logged_in_user'])) {
            echo "logged_in";
        } else {
            echo "not_logged_in";
        }
    }
}

$conn->close();
?>