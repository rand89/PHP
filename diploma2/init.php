<?php

session_start();
require_once "classes/Config.php";
require_once "classes/Database.php";
require_once "classes/Validate.php";
require_once "classes/Input.php";
require_once "classes/Token.php";
require_once "classes/Session.php";
require_once "classes/Cookie.php";
require_once "classes/Redirect.php";
require_once "classes/User.php";

$GLOBALS['config'] = [
    'mysql' => [
        'host' => 'localhost',
        'db' => 'project24',
        'user' => 'root',
        'pass' => ''
    ],
    'session' => [
        'token_name' => 'token',
        'user_session' => 'user'
    ],
    'cookie' => [
        'cookie_name' => 'hash',
        'cookie_expiry' => '1010'
    ]
];

if(Cookie::exists(Config::get('cookie.cookie_name')) && !Session::exists(Config::get('session.user_session'))) {
    $hash = Cookie::get(Config::get('cookie.cookie_name'));
    $hash_check = Database::getInstance()->get('user_sessions', ['hash', '=', $hash]);
    if($hash_check->count()) {
        $user = new User($hash_check->first()->user_id);
        $user->login();
    }
}

?>