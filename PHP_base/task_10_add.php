<?php

$data = $_POST['data'];
$pdo = new PDO("mysql:host=localhost; dbname=db24;", "root", "");
$query = "INSERT INTO data_table VALUES (:data)";
$statement = $pdo->prepare($query);
$statement->execute(['data' => $data]);

header("location: /task_10.php");

?>