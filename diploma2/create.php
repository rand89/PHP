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
            ],
            'username' => [
                'required' => true,
                'min' => 3
            ],
            'job' => ['required' => true],
            'phone' => ['required' => true],
            'address' => ['required' => true]
        ]);

        if($validation->passed()) {
            $user = new User;
            $user->create([
                'email' => Input::get('email'),
                'password' => password_hash(Input::get('password'), PASSWORD_DEFAULT),
                'username' => Input::get('username'),
                'job' => Input::get('job'),
                'status' => Input::get('status'),
                'image' => $user->upload_image(Input::get('image')),
                'phone' => Input::get('phone'),
                'address' => Input::get('address'),
                'vk' => Input::get('vk'),
                'telegram' => Input::get('telegram'),
                'instagram' => Input::get('instagram'),
                'tags' => "",
                'role' => "user"
            ]);

            Session::flash("success", "Пользователь добавлен.");
            Redirect::to("users.php");
        }
        else {
            foreach($validation->errors() as $error) {
                $errors .= $error . "<br />";
            }
            Session::flash("danger", $errors);
            Redirect::to("create_user.php");
        }
    }
}

?>