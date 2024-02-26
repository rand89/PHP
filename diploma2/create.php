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
            $user_id = $user->add_user(Input::get('email'), Input::get('password'));
            $user->edit_data($user_id, Input::get('username'), Input::get('job'), Input::get('phone'), Input::get('address'));
            $user->set_status($user_id, Input::get('status'));
            $user->upload_image($user_id, Input::get('image'));
            $user->add_links($user_id, Input::get('vk'), Input::get('telegram'), Input::get('instagram'));

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