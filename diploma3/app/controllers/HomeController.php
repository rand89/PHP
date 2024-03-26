<?php

namespace App\controllers;

use App\models\User;
use League\Plates\Engine;
use Respect\Validation\Validator as v;
use function Tamtamchik\SimpleFlash\flash;

class HomeController
{
    private $templates;
    private $user;
    
    public function __construct(Engine $engine, User $user)
    {
        $this->templates = $engine;
        $this->user = $user;
    }
    
    public function index()
    {
        echo $this->templates->render('page_register');
    }

    public function register()
    {
        if(!v::noWhitespace()->length(6, 50)->validate($_POST['password'])) {
            flash()->error('Длина пароля должна быть от 6 до 50 символов.');
            redirectTo('/');
        }

        $user_id = $this->user->add_user($_POST['email'], $_POST['password']);
        if($user_id) {
            flash()->success('Регистрация успешна.');
            redirectTo('/login');
        }
        redirectTo('/');
    }

    public function pageLogin()
    {
        echo $this->templates->render('page_login');
    }

    public function login()
    {
        if(!v::email()->validate($_POST['email'])) {
            flash()->error('Некорректный email.');
            redirectTo('/login');
        }
        
        $login = $this->user->login($_POST['email'], $_POST['password']);
        if($login) {
            redirectTo('/users');
        }
        redirectTo('/login');
    }

    public function logout()
    {
        $this->user->logout();
        redirectTo('/');
    }

    public function pageUsers()
    {
        $this->checkAuth();
        echo $this->templates->render('users', ['current_user' => $this->user]);
    }

    public function pageCreateUser()
    {
        $this->checkAuth();
        if(!$this->user->isAdmin()) {
            redirectTo('/login');
        }
        echo $this->templates->render('create_user', ['current_user' => $this->user]);
    }

    public function createUser()
    {
        $error = false;
        if(!v::email()->validate($_POST['email'])) {
            flash()->error('Некорректный email.');
            $error = true;
        }
        if(!v::noWhitespace()->length(6, 50)->validate($_POST['password'])) {
            flash()->error('Длина пароля должна быть от 6 до 50 символов.');
            $error = true;
        }
        if(!v::length(3, 50)->validate($_POST['username'])) {
            flash()->error('Длина имени должна быть от 3 до 50 символов.');
            $error = true;
        }
        if(!v::notEmpty()->validate($_POST['job'])) {
            flash()->error('Поле "Место работы" должно быть заполнено.');
            $error = true;
        }
        if(!v::notEmpty()->validate($_POST['phone'])) {
            flash()->error('Поле "Номер телефона" должно быть заполнено.');
            $error = true;
        }
        if(!v::notEmpty()->validate($_POST['address'])) {
            flash()->error('Поле "Адрес" должно быть заполнено.');
            $error = true;
        }

        if($error) {
            redirectTo('/create');
        }

        $user_id = $this->user->add_user($_POST['email'], $_POST['password']);
        if($user_id) {
            $this->user->edit_data($user_id, $_POST['username'], $_POST['job'], $_POST['phone'], $_POST['address']);
            $this->user->set_status($user_id, $_POST['status']);
            $this->user->upload_image($user_id, $_FILES['image']);
            $this->user->add_links($user_id, $_POST['vk'], $_POST['telegram'], $_POST['instagram']);
            flash()->success('Пользователь добавлен.');
            redirectTo('/users');
        }
        redirectTo('/create');
    }

    public function pageProfile($id)
    {
        $this->checkAuth();
        if(!$this->user->exists($id)) {
            redirectTo('/users');
        }
        echo $this->templates->render('page_profile', ['user' => $this->user->data($id)]);
    }

    public function pageEdit($id)
    {
        $this->checkAuth();
        $this->checkProfile($id);
        echo $this->templates->render('page_edit', ['user' => $this->user->data($id)]);
    }

    public function edit()
    {
        $error = false;
        $user_id = $_POST['user_id'];
        if(!v::length(3, 50)->validate($_POST['username'])) {
            flash()->error('Длина имени должна быть от 3 до 50 символов.');
            $error = true;
        }
        if(!v::notEmpty()->validate($_POST['job'])) {
            flash()->error('Поле "Место работы" должно быть заполнено.');
            $error = true;
        }
        if(!v::notEmpty()->validate($_POST['phone'])) {
            flash()->error('Поле "Номер телефона" должно быть заполнено.');
            $error = true;
        }
        if(!v::notEmpty()->validate($_POST['address'])) {
            flash()->error('Поле "Адрес" должно быть заполнено.');
            $error = true;
        }

        if($error) {
            redirectTo("/edit/$user_id");
        }

        $this->user->edit_data($user_id, $_POST['username'], $_POST['job'], $_POST['phone'], $_POST['address']);
        flash()->success('Профиль успешно обновлен.');
        redirectTo("/profile/$user_id");
    }

    public function pageStatus($id)
    {
        $this->checkAuth();
        $this->checkProfile($id);
        echo $this->templates->render('page_status',
            ['user_obj' => $this->user, 'user' => $this->user->data($id)]);
    }

    public function status()
    {
        $user_id = $_POST['user_id'];
        $this->user->set_status($user_id, $_POST['status']);
        flash()->success('Профиль успешно обновлен.');
        redirectTo("/profile/$user_id");
    }

    public function pagePhoto($id)
    {
        $this->checkAuth();
        $this->checkProfile($id);
        $user = $this->user->data($id);
        if($user->image) {
            $image = "/".$user->image;
        } else {
            $image = "/img/demo/avatars/avatar-m.png";
        }
        echo $this->templates->render('page_media', ['user' => $user, 'image' => $image]);
    }

    public function photo()
    {
        $user_id = $_POST['user_id'];
        if(!v::notEmpty()->validate($_FILES['image']['name'])) {
            flash()->error('Выберите аватар.');
            redirectTo("/photo/$user_id");
        }

        $this->user->delete_image($user_id);
        $this->user->upload_image($user_id, $_FILES['image']);
        flash()->success('Профиль успешно обновлен.');
        redirectTo("/profile/$user_id");
    }

    public function pageSecurity($id)
    {
        $this->checkAuth();
        $this->checkProfile($id);
        echo $this->templates->render('page_security', ['user' => $this->user->data($id)]);
    }

    public function security()
    {
        $user_id = $_POST['user_id'];
        if(!v::identical($_POST['password'])->validate($_POST['password_confirmation'])) {
            flash()->error('Пароли не совпадают.');
            redirectTo("/security/$user_id");
        }
        
        if($this->user->changeEmail($user_id, $_POST['email'])
        && $this->user->changePassword($user_id, $_POST['password'])) {
            flash()->success('Профиль успешно обновлен.');
            redirectTo("/profile/$user_id");
        }
        redirectTo("/security/$user_id");
    }

    public function deleteUser($id)
    {
        $this->checkAuth();
        $this->checkProfile($id);
        $this->user->delete($id);
        if($this->user->is_equal($id)) {
            redirectTo('/logout');
        }
        flash()->success('Пользователь удален.');
        redirectTo('/users');
    }

    private function checkAuth()
    {
        if (!$this->user->isLoggedIn()) {
            redirectTo('/login');
        }
    }

    private function checkProfile($id)
    {
        if(!$this->user->isAdmin()) {
            if(!$this->user->is_equal($id)) {
                flash()->error('Можно редактировать только свой профиль.');
                redirectTo('/users');
            }
        }
    }
}

?>