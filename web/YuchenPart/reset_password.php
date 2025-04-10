<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>更改密码</h2>
        <form id="resetPasswordForm">
            <div class="form-group">
                <label for="username">用户名：</label>
                <input type="text" id="username" name="username" required>
                <span id="usernameError" class="error"></span>
            </div>
            <div class="form-group">
                <label for="newPassword">新密码：</label>
                <input type="password" id="newPassword" name="newPassword" required>
                <span id="newPasswordError" class="error"></span>
            </div>
            <div class="form-group">
                <label for="confirmPassword">确认新密码：</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
                <span id="confirmPasswordError" class="error"></span>
            </div>
            <button type="submit">提交更改</button>
        </form>
    </div>

    <script>
        document.getElementById("resetPasswordForm").addEventListener("submit", function(e) {
            e.preventDefault();

            let username = document.getElementById("username").value.trim();
            let newPassword = document.getElementById("newPassword").value.trim();
            let confirmPassword = document.getElementById("confirmPassword").value.trim();
            let usernameError = document.getElementById("usernameError");
            let newPasswordError = document.getElementById("newPasswordError");
            let confirmPasswordError = document.getElementById("confirmPasswordError");

            // 清空错误提示
            usernameError.textContent = "";
            newPasswordError.textContent = "";
            confirmPasswordError.textContent = "";

            // 客户端验证
            if (username === "") {
                usernameError.textContent = "请输入用户名";
                return;
            }
            if (newPassword === "") {
                newPasswordError.textContent = "请输入新密码";
                return;
            }
            if (confirmPassword === "") {
                confirmPasswordError.textContent = "请确认新密码";
                return;
            }
            if (newPassword !== confirmPassword) {
                confirmPasswordError.textContent = "新密码与确认密码不一致";
                return;
            }

            // 发送请求到后端
            let formData = new FormData();
            formData.append("action", "reset_password");
            formData.append("username", username);
            formData.append("new_password", newPassword);

            fetch("login.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data === "success") {
                    alert("密码更改成功！");
                    window.location.href = "Content.php"; // 跳转回登录页
                } else {
                    alert(data); // 显示错误信息
                }
            })
            .catch(error => console.error("Error:", error));
        });
    </script>
</body>
</html>