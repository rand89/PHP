<?php
session_start();

$login = $_POST['email'];
$pass = $_POST['password'];
$pdo = new PDO("mysql:host=localhost; dbname=db24;", "root", "");
$query = "SELECT * FROM workers WHERE login = :login";
$statement = $pdo->prepare($query);
$statement->execute(["login" => $login]);
$worker = $statement->fetch(PDO::FETCH_ASSOC);

if(!empty($worker))
{
    $msg = "Этот эл. адрес уже занят другим пользователем.";
    $_SESSION['danger'] = $msg;

    header("Location: /task_12.php");
    exit;
}

$pass = password_hash($pass, PASSWORD_DEFAULT);

$query = "INSERT INTO workers VALUES (NULL, :login, :pass)";
$statement = $pdo->prepare($query);
$statement->execute(["login" => $login, "pass" => $pass]);

header("Location: /task_12.php");
?>