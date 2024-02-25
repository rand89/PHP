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
                'required' => true
            ]
        ]);

        if($validation->passed()) {
            $user = new User;
            $remember = (Input::get('remember')) == 'on' ? true : false;
            $login = $user->login(Input::get('email'), Input::get('password'), $remember);
            if($login) {
                Redirect::to("users.php");
            }
            Session::flash("danger", "Неверный логин или пароль.");
            Redirect::to("page_login.php");  
        }
        else {
            foreach($validation->errors() as $error) {
                $errors .= $error . "<br />";
            }
            Session::flash("danger", $errors);
            Redirect::to("page_login.php");
        }
    }
}
?>