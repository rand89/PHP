<?php
session_start();

$_SESSION['msg'] = $_POST['msg'];
header("Location: /task_13.php");
?>