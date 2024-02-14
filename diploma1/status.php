<?php
session_start();
require "functions.php";

$user_id = $_POST['user_id'];

set_status($user_id, $_POST['status']);
set_flash_message("success", "Профиль успешно обновлен.");
redirect_to("page_profile.php?id=$user_id");
?>