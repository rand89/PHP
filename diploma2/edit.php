<?php
require_once "init.php";

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, [
            'username' => [
                'required' => true,
                'min' => 3
            ],
            'job' => ['required' => true],
            'phone' => ['required' => true],
            'address' => ['required' => true]
        ]);

        $user_id = Input::get('user_id');
        if($validation->passed()) {
            $user = new User();
            $user->edit_data($user_id, Input::get('username'), Input::get('job'), Input::get('phone'), Input::get('address'));
            Session::flash("success", "Профиль успешно обновлен.");
            Redirect::to("page_profile.php?id={$user_id}");  
        }
        else {
            foreach($validation->errors() as $error) {
                $errors .= $error . "<br />";
            }
            Session::flash("danger", $errors);
            Redirect::to("page_edit.php?id={$user_id}");
        }
    }
}

?>