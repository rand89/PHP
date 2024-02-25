<?php
require_once "init.php";

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, [
            'email' => [
                'required' => true,
                'email' => true
            ],
            'password' => [
                'required' => true,
                'min' => 6
            ],
            'password_confirmation' => [
                'required' => true,
                'min' => 6,
                'matches' => 'password'
            ]
        ]);

        $user_id = Input::get('user_id');
        if($validation->passed()) {
            $user = new User($user_id);

            if(!$user->free_email(Input::get('email'))) {
                Session::flash("danger", "Введенный email занят.");
                Redirect::to("page_security.php?id={$user_id}");
            }

            $user->update([
                'email' => Input::get('email'),
                'password' => password_hash(Input::get('password'), PASSWORD_DEFAULT)
            ], $user_id );
            Session::flash("success", "Профиль успешно обновлен.");
            Redirect::to("page_profile.php?id={$user_id}");  
        }
        else {
            foreach($validation->errors() as $error) {
                $errors .= $error . "<br />";
            }
            Session::flash("danger", $errors);
            Redirect::to("page_security.php?id={$user_id}");
        }
    }
}

?>