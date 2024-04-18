<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    private $user;
    
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    
    public function index()
    {
        return view('page_register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5'
        ]);

        $user_id = $this->user->addUser($request->email, $request->password);
        if($user_id) {
            $request->session()->flash('success', "Регистрация успешна.");
            return redirect(route('pageLogin'));
        }
        return redirect(route('pageRegister'));
    }

    public function pageLogin()
    {
        return view('page_login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(auth()->attempt($data)) {
            return redirect(route('index'));
        }
        return redirect(route('pageLogin'))->withErrors("Неверный логин или пароль.");
    }

    public function logout()
    {
        auth()->logout();
        return redirect(route('pageRegister'));
    }
}
