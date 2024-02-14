<?php
session_start();
require "functions.php";

$email = $_POST['email'];

if(!empty(get_user_by_email($email)))
{
    set_flash_message("danger", "Этот эл. адрес уже занят другим пользователем.");
    redirect_to("create_user.php");
}

$user_id = add_user($email, $_POST['password']);
edit_data($user_id, $_POST['username'], $_POST['job'], $_POST['phone'], $_POST['address']);
set_status($user_id, $_POST['status']);
upload_image($user_id, $_FILES['image']);
add_links($user_id, $_POST['vk'], $_POST['telegram'], $_POST['instagram']);

set_flash_message("success", "Пользователь добавлен.");
redirect_to("users.php");
?>