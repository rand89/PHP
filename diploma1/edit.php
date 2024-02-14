<?php
session_start();
require "functions.php";

$user_id = $_POST['user_id'];
edit_data($user_id, $_POST['username'], $_POST['job'], $_POST['phone'], $_POST['address']);
set_flash_message("success", "Профиль успешно обновлен.");
redirect_to("page_profile.php?id=$user_id");
?>