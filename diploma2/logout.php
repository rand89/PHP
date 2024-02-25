<?php
require_once "init.php";
$user = new User;
$user->logout();
Redirect::to("page_register.php");
?>