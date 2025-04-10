<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Email and Change Password</title>
    <script src="https://cdn.emailjs.com/dist/email.min.js"></script>
</head>
<body>
    <div>
        <h2>发送邮件</h2>
        <form id="emailForm">
            <label for="email">收件人邮箱：</label>
            <input type="email" id="email" name="to_email" required>
            <input type="hidden" name="random_number" id="randomNumber">
            <button type="submit" id="sendEmailBtn">发送邮件</button>
            <span id="timerText"></span> <!-- 显示倒计时 -->
        </form>

        <h2>更改密码</h2>
        <form id="pincodeForm">
            <h3>Enter the pincode to change password.</h3>
            <input type="password" id="inputpincode" placeholder="Enter pincode here">
            <span id="Errorpincode"></span>
            <button id="submitpincode" type="button">SUBMIT</button>
        </form>
    </div>

    <script>
        // 初始化 EmailJS
        (function(){
            emailjs.init("WNuxY682Ux6oPiCUN");  // 你的 EmailJS Public Key
        })();

        let sentRandomNumber = null; // 存储发送的 OTP
        let countdownTimer = null; // 计时器变量
        let countdown = 40; // 倒计时 40 秒
        let sendAttempt = 0; // 发送邮件的次数
        const maxAttempts = 3; // 限制最多发送 3 次
        const timerText = document.getElementById("timerText");
        const sendEmailBtn = document.getElementById("sendEmailBtn");

        // 生成随机 4 位数 OTP
        function generateRandomNumber() {
            return Math.floor(1000 + Math.random() * 9000);
        }

        // 发送邮件功能
        document.getElementById("emailForm").addEventListener("submit", function(event) {
            event.preventDefault(); // 阻止默认提交行为
            
            if (sendAttempt >= maxAttempts) {
                alert("您已经达到最大 OTP 发送次数，即将跳转到首页！");
                window.location.href = "Content.php"; // 直接跳转到 Content.php
                return;
            }

            if (countdown > 0 && sentRandomNumber !== null) {
                alert(`请等待 ${countdown} 秒后再发送邮件！`);
                return;
            }

            sendOTP();
        });

        // 发送 OTP 并启动倒计时
        function sendOTP() {
            const randomNum = generateRandomNumber();
            sentRandomNumber = randomNum.toString();
            document.getElementById("randomNumber").value = randomNum;
            sendAttempt++; // 增加发送次数

            emailjs.sendForm("service_9q3qmh4", "template_g61ng7d", document.getElementById("emailForm"))
                .then(response => {
                    alert("邮件发送成功！随机数: " + randomNum);
                    console.log("Success:", response);
                    startCountdown();
                })
                .catch(error => {
                    alert("邮件发送失败！");
                    console.error("Error:", error);
                    sentRandomNumber = null;
                });

            // 检查是否达到最大次数
            if (sendAttempt >= maxAttempts) {
                alert("您已达到最大 OTP 发送次数，即将跳转到首页！");
                setTimeout(() => window.location.href = "Content.php", 3000); // 3 秒后自动跳转
            }
        }

        // 开始倒计时
        function startCountdown() {
            clearInterval(countdownTimer); // 先清除可能存在的计时器
            countdown = 40;
            sendEmailBtn.disabled = true; // 禁用发送按钮
            timerText.textContent = `OTP 有效时间: ${countdown} 秒`;

            countdownTimer = setInterval(() => {
                countdown--;
                timerText.textContent = `OTP 有效时间: ${countdown} 秒`;

                if (countdown <= 0) {
                    clearInterval(countdownTimer);
                    sentRandomNumber = null;
                    timerText.textContent = "OTP 已过期，请重新发送邮件！";
                    sendEmailBtn.disabled = false; // 重新启用发送按钮
                }
            }, 1000);
        }

        // 处理 pincode 输入
        document.getElementById("submitpincode").addEventListener("click", function(event) {
            event.preventDefault();
            let userpincode = document.getElementById("inputpincode").value.trim();
            let pincodeError = document.getElementById("Errorpincode");

            pincodeError.textContent = ""; // 清空错误提示

            if (userpincode === "") {
                pincodeError.textContent = "请输入 pincode";
            } else if (sentRandomNumber === null) {
                pincodeError.textContent = "OTP 已过期，请重新发送邮件";
            } else if (userpincode === sentRandomNumber) {
                clearInterval(countdownTimer); // 停止倒计时
                window.location.href = "reset_password.php";
            } else {
                pincodeError.textContent = "pincode 错误，请重新输入";
                document.getElementById("inputpincode").value = "";
            }
        });
    </script>
</body>
</html>
