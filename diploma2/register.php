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
            $user->create([
                'email' => Input::get('email'),
                'password' => password_hash(Input::get('password'), PASSWORD_DEFAULT),
                'username' => "",
                'job' => "",
                'status' => "success",
                'image' => "",
                'phone' => "",
                'address' => "",
                'vk' => "",
                'telegram' => "",
                'instagram' => "",
                'tags' => "",
                'role' => "user"
            ]);

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