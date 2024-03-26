<?php

namespace App\models;

use function Tamtamchik\SimpleFlash\flash;
use Delight\Auth\Auth;

class User
{
    private $db;
    private $auth;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
    }

    public function data($id_value)
    {
        return $this->db->get('users', 'id', $id_value);
    }

    public function login($email, $password)
    {
        try {
            $this->auth->login($email, $password);
            return true;
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Неверный логин или пароль.');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Неверный логин или пароль.');
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            flash()->error('Почта не подтверждена.');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Слишком много запросов.');
        }

        return false;
    }

    public function isLoggedIn()
    {
        if ($this->auth->isLoggedIn()) {
            return true;
        }
        return false;
    }

    public function isAdmin()
    {
        if($this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
            return true;
        }
        return false;
    }

    public function is_equal($user_id) {
        return $user_id == $this->auth->id();
    }

    public function getAllUsers()
    {
        return $this->db->getAll('users');
    }

    public function exists($id)
    {
        if(!empty($this->data($id))) {
            return true;
        }
        return false;
    }

    public function get_statuses()
    {
        $statuses = [
            "success" => "Онлайн",
            "warning" => "Отошел",
            "danger" => "Не беспокоить"
        ];
        return $statuses;
    }

    public function add_user($email, $password)
    {
        try {
            $user_id = $this->auth->register($email, $password);
            return $user_id;
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Некорректный адрес почты.');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Некорректный пароль');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('Такой пользователь уже существует.');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Слишком много запросов.');
        }

        return false;
    }

    public function edit_data($user_id, $name, $job, $phone, $address)
    {
        $data = [
            'username' => $name,
            'job' => $job,
            'phone' => $phone,
            'address' => $address
        ];
        $this->db->update('users', $data, 'id', $user_id);
    }

    private function becomeAdmin($id_admin)
    {
        if($id_admin) {
            $this->auth->admin()->logInAsUserById($id_admin);
        }
    }

    public function changeEmail($user_id, $email)
    {
        $id_admin = 0;

        if($this->isAdmin()) {
            $id_admin = $this->auth->id();
            $this->auth->admin()->logInAsUserById($user_id);
        }
        
        if($email == $this->auth->getEmail()) {
            $this->becomeAdmin($id_admin);
            return true;
        }
        
        try {
            $this->auth->changeEmail($email, function($selector, $token) {
                $this->auth->confirmEmail($selector, $token);
            });
            $this->becomeAdmin($id_admin);
            return true;
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Некорректный адрес почты.');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('Такой пользователь уже существует.');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Слишком много запросов.');
        }

        $this->becomeAdmin($id_admin);
        return false;
    }

    public function changePassword($user_id, $password)
    {
        try {
            $this->auth->admin()->changePasswordForUserById($user_id, $password);
            return true;
        }
        catch (\Delight\Auth\UnknownIdException $e) {
            flash()->error('Неизвестный ID.');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Некорректный пароль.');
        }

        return false;
    }

    public function set_status($user_id, $status)
    {
        $data = ['my_status' => $status];
        $this->db->update('users', $data, 'id', $user_id);
    }

    public function add_links($user_id, $vk, $telegram, $instagram)
    {
        $data = [
            'vk' => $vk,
            'telegram' => $telegram,
            'instagram' => $instagram
        ];
        $this->db->update('users', $data, 'id', $user_id);
    }

    public function upload_image($user_id, $image) {
        if($image['name'] == "") {
            return '';
        }
        $dir = 'img/demo/avatars/';
        $name = $image['name'];
        $path_info = pathinfo($name);
        $name = uniqid().".".$path_info['extension'];
        move_uploaded_file($image['tmp_name'], $dir.$name);
        $data = ['image' => $dir.$name];
        $this->db->update('users', $data, 'id', $user_id);
    }

    public function delete_image($user_id) {
        $image_old = $this->data($user_id)->image;
        if($image_old != "" && file_exists($image_old)) {
            unlink($image_old);
        }
    }

    public function logout()
    {
        $this->auth->logOut();
    }

    public function delete($user_id)
    {
        $this->delete_image($user_id);
        try {
            $this->auth->admin()->deleteUserById($user_id);
            return true;
        }
        catch (\Delight\Auth\UnknownIdException $e) {
            flash()->error('Неизвестный ID');
        }
        return false;
    }
}

?>