<?php

$dir = 'upload/';
$name = $_FILES['filename']['name'];
$path_info = pathinfo($name);
$name = uniqid().".".$path_info['extension'];

move_uploaded_file($_FILES['filename']['tmp_name'], $dir.$name);

$pdo = new PDO("mysql:host=localhost; dbname=db24;", "root", "");
$sql = "INSERT INTO images (name) VALUES (:name)";
$statement = $pdo->prepare($sql);
$statement->execute(["name" => $name]);

header("Location: /task_17.php");
?>