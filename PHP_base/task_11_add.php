<?php
session_start();

$data = $_POST['data'];
$pdo = new PDO("mysql:host=localhost; dbname=db24;", "root", "");
$query = "SELECT * FROM data_table WHERE data=:data";
$statement = $pdo->prepare($query);
$statement->execute(["data" => $data]);
$texts = $statement->fetch(PDO::FETCH_ASSOC);

if(!empty($texts))
{
    $msg = "Такая запись уже существует.";
    $_SESSION['danger'] = $msg;

    header("Location: /task_11.php");
    exit;
}

$query = "INSERT INTO data_table (data) VALUES (:data)";
$statement = $pdo->prepare($query);
$statement->execute(["data" => $data]);
$msg = "Запись добавлена.";
$_SESSION['success'] = $msg;

header("Location: /task_11.php");

?>