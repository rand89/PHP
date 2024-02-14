<?php
session_start();
require "functions.php";

if(is_not_logged_in())
{
    redirect_to("page_login.php");
}

$auth_user = get_auth_user();
$edit_user_id = $_GET['id'];
if(!is_admin($auth_user))
{
    if(!is_author($auth_user['id'], $edit_user_id))
    {
        set_flash_message("danger", "Можно редактировать только свой профиль.");
        redirect_to("users.php");
    }
}

delete($edit_user_id);

if(is_author($auth_user['id'], $edit_user_id))
{
    redirect_to("logout.php");
}
set_flash_message("success", "Пользователь удален.");
redirect_to("users.php");
?>