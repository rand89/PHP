<?php
require_once "init.php";

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_FILES['image'], [
            'name' => ['required' => true]
        ]);

        $user_id = Input::get('user_id');
        if($validation->passed()) {
            $user = new User($user_id);
            $user->delete_image();
            $user->upload_image($user_id, Input::get('image'));
            Session::flash("success", "Профиль успешно обновлен.");
            Redirect::to("page_profile.php?id={$user_id}");  
        }
        else {
            foreach($validation->errors() as $error) {
                $errors .= $error . "<br />";
            }
            Session::flash("danger", $errors);
            Redirect::to("page_media.php?id={$user_id}");
        }
    }
}

?>