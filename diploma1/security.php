<?php
session_start();
require "functions.php";

$user_id = $_POST['user_id'];

if(!is_email_free($_POST['email'], $user_id))
{
    set_flash_message("danger", "Введенный email занят.");
    redirect_to("page_security.php?id=$user_id");
}

edit_security($user_id, $_POST['email'], $_POST['password']);
set_flash_message("success", "Профиль успешно обновлен.");
redirect_to("page_profile.php?id=$user_id");
?>