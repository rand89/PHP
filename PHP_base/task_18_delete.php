<?php

$dir = 'upload/';
$id_image = $_GET['id'];

$pdo = new PDO("mysql:host=localhost;dbname=db24", "root", "");
$sql = "SELECT * FROM images WHERE id = :id";
$statement = $pdo->prepare($sql);
$statement->execute(["id" => $id_image]);
$image = $statement->fetch(PDO::FETCH_ASSOC);

if (file_exists($dir.$image['name']))
{
    unlink($dir.$image['name']);
}

$sql = "DELETE FROM images WHERE id = :id";
$statement = $pdo->prepare($sql);
$statement->execute(["id" => $id_image]);

header("Location: /task_18.php");
?>