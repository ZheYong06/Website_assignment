<!DOCTYPE html>
<html>
    <head>
        <title> Admin Login Page </title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="square"> 
            <h1> Admin Login <hr></h1>
            <form id="loginForm">
                <h3> Username: </h3>
                <input id="NAME" name="username" type="text" placeholder="Enter username" required>
                <span id="usernameError" style="color: red;"></span>
                
                <h3>Password:</h3>
                <input id="Password" name="password" type="password" placeholder="Enter password" required>
                <span id="passwordError" style="color: red;"></span>
                
                <h5 id="special"><a href="testing.php">Forgot password?</a></h5>
            </form>
            <button id="LOGINbutton"> Log in</button>

            <script>
                document.getElementById("LOGINbutton").addEventListener("click", function(e) {
                    e.preventDefault();
                    
                    let Username = document.getElementById("NAME").value.trim();
                    let Password = document.getElementById("Password").value;
                    let usernameError = document.getElementById("usernameError");
                    let passwordError = document.getElementById("passwordError");
                    
                    usernameError.textContent = ""; 
                    passwordError.textContent = "";
                    
                    if (Username === "") {
                        usernameError.textContent = "Please fill out username";
                        return;
                    }
                    if (Password === "") {
                        passwordError.textContent = "Please fill out password";
                        return;
                    }

                    let formData = new FormData();
                    formData.append("username", Username);
                    formData.append("password", Password);
                    
                    fetch("login.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        console.log("Server response:", data); // 检查 PHP 返回的数据
                        if (data === "success") {
                            window.location.href = "Aftersignin.html";
                        } else {
                            alert(data);
                        }
                    })
                    .catch(error => console.error("Error:", error));
                });
            </script>
        </div>
    </body>
</html>
