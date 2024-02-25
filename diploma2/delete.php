<?php
require_once "init.php";
$current_user = new User();
if(!$current_user->is_logged_in()) {
    Redirect::to("page_login.php");
}

$edit_user_id = Input::get('id');
if(!$current_user->is_admin()) {
    if(!$current_user->is_equal($edit_user_id)) {
        Session::flash("danger", "Можно редактировать только свой профиль.");
        Redirect::to("users.php");
    }
}

$user = new User($edit_user_id);
$user->delete();

if($current_user->is_equal($edit_user_id)) {
    Redirect::to("logout.php");
}
Session::flash("success", "Пользователь удален.");
Redirect::to("users.php");
?>