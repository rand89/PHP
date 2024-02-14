<?php

function new_pdo()
{
    return new PDO("mysql:host=localhost; dbname=project24;", "root", "");
}

function get_user_by_email($email)
{
    $pdo = new_pdo();
    $sql = "SELECT * FROM users WHERE email=:email";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

function get_user_by_id($user_id)
{
    $pdo = new_pdo();
    $sql = "SELECT * FROM users WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(["id" => $user_id]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

function add_user($email, $password)
{
    $password = password_hash($password, PASSWORD_DEFAULT);
    $pdo = new_pdo();
    $sql = "INSERT INTO users VALUES (NULL, :email, :password, '', '', '', '', '', '', '', '', '', '', 'user')";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email, "password" => $password]);
    return $pdo->lastInsertId();
}

function edit_data($user_id, $name, $job, $phone, $address)
{
    $pdo = new_pdo();
    $sql = "UPDATE users SET username=:name, job=:job, phone=:phone, address=:address WHERE id=:user_id ";
    $statement = $pdo->prepare($sql);
    $statement->execute(["name" => $name, "job" => $job, "phone" => $phone, "address" => $address, "user_id" => $user_id]);
}

function edit_security($user_id, $email, $password)
{
    $password = password_hash($password, PASSWORD_DEFAULT);
    $pdo = new_pdo();
    $sql = "UPDATE users SET email=:email, password=:password WHERE id=:user_id ";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email, "password" => $password, "user_id" => $user_id]);
}

function set_status($user_id, $status)
{
    $pdo = new_pdo();
    $sql = "UPDATE users SET status=:status WHERE id=:user_id ";
    $statement = $pdo->prepare($sql);
    $statement->execute(["status" => $status, "user_id" => $user_id]);
}

function get_image($user_id)
{
    $pdo = new_pdo();
    $sql = "SELECT image FROM users WHERE id=:user_id";
    $statement = $pdo->prepare($sql);
    $statement->execute(["user_id" => $user_id]);
    $image_old = $statement->fetch(PDO::FETCH_ASSOC);
    return $image_old['image'];
}

function delete_image($user_id)
{
    $image_old = get_image($user_id);
    if($image_old != "" && file_exists($image_old))
    {
        unlink($image_old);
    }
}

function upload_image($user_id, $image)
{
    delete_image($user_id);
    $dir = 'img/demo/avatars/';
    $name = $image['name'];
    $path_info = pathinfo($name);
    $name = uniqid().".".$path_info['extension'];
    move_uploaded_file($image['tmp_name'], $dir.$name);

    $pdo = new_pdo();
    $sql = "UPDATE users SET image=:image WHERE id=:user_id ";
    $statement = $pdo->prepare($sql);
    $statement->execute(["image" => $dir.$name, "user_id" => $user_id]);
}

function add_links($user_id, $vk, $telegram, $instagram)
{
    $pdo = new_pdo();
    $sql = "UPDATE users SET vk=:vk, telegram=:telegram, instagram=:instagram WHERE id=:user_id ";
    $statement = $pdo->prepare($sql);
    $statement->execute(["vk" => $vk, "telegram" => $telegram, "instagram" => $instagram, "user_id" => $user_id]);
}

function delete($user_id)
{
    delete_image($user_id);
    $pdo = new_pdo();
    $sql = "DELETE FROM users WHERE id=:user_id";
    $statement = $pdo->prepare($sql);
    $statement->execute(["user_id" => $user_id]);
}

function set_flash_message($name, $message)
{
    $_SESSION[$name] = $message;
}

function display_flash_message($name)
{
    if(isset($_SESSION[$name]))
    {
        echo "<div class=\"alert alert-{$name} text-dark\" role=\"alert\">{$_SESSION[$name]}</div>";
        unset($_SESSION[$name]);
    }
}

function redirect_to($path)
{
    header("Location: /".$path);
    exit;
}

function login($email, $password)
{
    $user = get_user_by_email($email);
    if(empty($user) || !password_verify($password, $user['password']))
    {
        set_flash_message("danger", "Неверный логин или пароль");
        return false;
    }

    $_SESSION["user"] = [ "id" => $user["id"], "role" => $user["role"] ];
    return true;
}

function is_logged_in()
{
    if(isset($_SESSION["user"])) return true;
    return false;
}

function is_not_logged_in()
{
    return !is_logged_in();
}

function get_users()
{
    $pdo = new_pdo();
    $sql = "SELECT * FROM users";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function get_auth_user()
{
    if(is_logged_in()) return $_SESSION["user"];
    return false;
}

function is_admin($user)
{
    if(is_logged_in() && $user["role"] == "admin") return true;
    return false;
}

function is_equal($user, $current_user)
{
    return $user["id"] == $current_user["id"];
}

function is_author($auth_user_id, $edit_user_id)
{
    return $auth_user_id == $edit_user_id;
}

function is_email_free($email, $user_id)
{
    $user = get_user_by_email($email);
    $current_user = get_user_by_id($user_id);
    if(empty($user) || $email == $current_user['email'])
    {
        return true;
    }
    return false;
}

function logout()
{
    unset($_SESSION["user"]);
    redirect_to("page_register.php");
}

?>