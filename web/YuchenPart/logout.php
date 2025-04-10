<?php
session_start();
session_unset(); // 清空所有 session 变量
session_destroy(); // 销毁 session
header("Location: c/a/code_ass/web/program/address.php"); // 跳转回首页
exit();
?>
