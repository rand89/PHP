<?php
require_once "init.php";

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, [
            'email' => [
                'required' => true,
                'email' => true,
                'unique' => 'users'
            ],
            'password' => [
                'required' => true,
                'min' => 6
            ]
        ]);

        if($validation->passed()) {
            $user = new User;
            $user_id = $user->add_user(Input::get('email'), Input::get('password'));
            Session::flash("success", "Регистрация успешна");
            Redirect::to("page_login.php");
        }
        else {
            foreach($validation->errors() as $error) {
                $errors .= $error . "<br />";
            }
            Session::flash("danger", $errors);
            Redirect::to("page_register.php");
        }
    }
}

?>