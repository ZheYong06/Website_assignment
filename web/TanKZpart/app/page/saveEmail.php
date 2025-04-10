    <?php

    include 'c:\xampp\htdocs\a\code_ass\web\app\lib\database.php';
    session_start();
    $user_id=$_SESSION["user_id"];
    $email=$_POST["email"] ?? "";

    $conn = new mysqli('localhost','root','','online_shopping');

    if ($conn->connect_errno)
    die("database failed" . $conn->connect_errno);


    $stmt = $conn->prepare("UPDATE user_profile SET email = ? WHERE user_id =?");

    $stmt ->bind_param("si",$email,$user_id);
    $stmt ->execute();
    $stmt ->close();
    $conn ->close();


    ?>

