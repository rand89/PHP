<?php
session_start();
unset($_SESSION['worker']);
header("Location: /task_15.php");
?>