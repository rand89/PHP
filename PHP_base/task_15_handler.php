<?php
session_start();

$login = $_POST['login'];
$pass = $_POST['password'];
$pdo = new PDO("mysql:host=localhost; dbname=db24;", "root", "");
$query = "SELECT * FROM workers WHERE login = :login";
$statement = $pdo->prepare($query);
$statement->execute(["login" => $login]);
$worker = $statement->fetch(PDO::FETCH_ASSOC);

if(empty($worker) || !password_verify($pass, $worker['pass']))
{
    $_SESSION['danger'] = "Неверный логин или пароль";
    header("Location: /task_15.php");
    exit;
}

$_SESSION['worker'] = [ "id" => $worker["id"], "login" => $worker["login"] ];
header("Location: /task_16.php");
?>