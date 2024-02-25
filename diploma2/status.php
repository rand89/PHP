<?php
require_once "init.php";

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, [
            'status' => ['required' => true]
        ]);

        $user_id = Input::get('user_id');
        if($validation->passed()) {
            $user = new User();
            $user->update([
                'status' => Input::get('status')
            ], $user_id );
            Session::flash("success", "Профиль успешно обновлен.");
            Redirect::to("page_profile.php?id={$user_id}");  
        }
        else {
            foreach($validation->errors() as $error) {
                $errors .= $error . "<br />";
            }
            Session::flash("danger", $errors);
            Redirect::to("page_status.php?id={$user_id}");
        }
    }
}

?>