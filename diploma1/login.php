<?php
session_start();
require "functions.php";

$email = $_POST['email'];
$password = $_POST['password'];

if(login($email, $password))
{
    redirect_to("users.php");
}
redirect_to("page_login.php");
?>