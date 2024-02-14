<?php
session_start();
require "functions.php";

$user_id = $_POST['user_id'];

upload_image($user_id, $_FILES['image']);
set_flash_message("success", "Профиль успешно обновлен.");
redirect_to("page_profile.php?id=$user_id");
?>