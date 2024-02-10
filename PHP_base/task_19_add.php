<?php

for ($j=0; $j < count($_FILES['filename']['name']); $j++)
{
    upload($_FILES['filename']['name'][$j], $_FILES['filename']['tmp_name'][$j]);
}

header("Location: /task_19.php");


function upload($name, $tmp_name)
{
    $dir = 'upload/';
    $path_info = pathinfo($name);
    $name = uniqid().".".$path_info['extension'];
    move_uploaded_file($tmp_name, $dir.$name);

    $pdo = new PDO("mysql:host=localhost; dbname=db24;", "root", "");
    $sql = "INSERT INTO images (name) VALUES (:name)";
    $statement = $pdo->prepare($sql);
    $statement->execute(["name" => $name]);
}
?>