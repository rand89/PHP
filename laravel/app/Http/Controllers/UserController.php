<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    private $user;
    
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    
    public function index()
    {
        return view('users', ['current_user' => $this->user]);
    }

    public function create()
    {
        if(!$this->user->isAdmin()) {
            return redirect(route('pageLogin'));
        }
        return view('create_user', ['current_user' => $this->user]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
            'username' => 'required|min:3',
            'job' => 'required',
            'phone' => 'required',
            'address' => 'required'
        ]);

        $user_id = $this->user->addUser($request->email, $request->password);
        if($user_id) {
            $this->user->edit_data($user_id, $request->username, $request->job, $request->phone, $request->address);
            $this->user->set_status($user_id, $request->status);
            $this->user->upload_image($user_id, $request->image);
            $this->user->add_links($user_id, $request->vk, $request->telegram, $request->instagram);
            $request->session()->flash('success', "Пользователь добавлен.");
            return redirect(route('index'));
        }
        return redirect(route('create'));
    }

    public function show(User $user)
    {
        return view('page_profile', compact('user'));
    }

    public function editInfo($id)
    {
        return view('page_edit', ['user' => $this->user->find($id)]);
    }

    public function updateInfo($id, Request $request)
    {
        $request->validate([
            'username' => 'required|min:3',
            'job' => 'required',
            'phone' => 'required',
            'address' => 'required'
        ]);

        $this->user->edit_data($id, $request->username, $request->job, $request->phone, $request->address);
        $request->session()->flash('success', "Профиль успешно обновлен.");
        return redirect(route('show', $id));
    }

    public function editStatus($id)
    {
        return view('page_status', ['user' => $this->user->find($id)]);
    }

    public function updateStatus($id, Request $request)
    {
        $this->user->set_status($id, $request->status);
        $request->session()->flash('success', "Профиль успешно обновлен.");
        return redirect(route('show', $id));
    }

    public function editPhoto($id)
    {
        $user = $this->user->find($id);
        if($user->image) {
            $image = "/".$user->image;
        } else {
            $image = "/img/demo/avatars/avatar-m.png";
        }
        return view('page_media', ['user' => $user, 'image' => $image]);
    }

    public function updatePhoto($id, Request $request)
    {
        $request->validate([
            'image' => 'required'
        ]);

        $this->user->delete_image($id);
        $this->user->upload_image($id, $request->image);
        $request->session()->flash('success', 'Профиль успешно обновлен.');
        return redirect(route('show', $id));
    }

    public function editSecurity($id)
    {
        return view('page_security', ['user' => $this->user->find($id)]);
    }

    public function updateSecurity($id, Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5|confirmed'
        ],
        ['password.confirmed' => 'Пароли не совпадают!']);
       
        if(!$this->user->free_email($id, $request->email)) {
            $request->session()->flash("error", "Введенный email занят.");
            return redirect(route('editSecurity', $id));
        }

        $this->user->edit_security($id, $request->email, $request->password);
        $request->session()->flash('success', 'Профиль успешно обновлен.');
        return redirect(route('show', $id));
    }

    public function destroy($id, Request $request)
    {
        $this->user->deleteUser($id);
        $request->session()->flash('success', "Пользователь удален.");
        return redirect(route('index'));
    }
}
