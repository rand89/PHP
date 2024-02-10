<?php
session_start();

if(isset($_POST["clear"]))
{
    unset($_SESSION['number']);
    header("Location: /task_14.php");
    exit;
}

$_SESSION['number']++;
header("Location: /task_14.php");
?>