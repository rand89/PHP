<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function addUser($email, $password)
    {
        $user_id = self::insertGetId([
            'email' => $email,
            'password' => Hash::make($password)
        ]);
        return $user_id;
    }

    public function isAdmin()
    {
        $user = self::find(auth()->id());
        if($user->role == 'admin') {
            return true;
        }
        return false;
    }

    public function is_equal($user_id)
    {
        return $user_id == auth()->id();
    }

    public function getAllUsers()
    {
        return self::all();
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

    public function edit_data($user_id, $name, $job, $phone, $address)
    {
        $user = self::find($user_id);
        $user->username = $name;
        $user->job = $job;
        $user->phone = $phone;
        $user->address = $address;
        $user->save();
    }

    public function set_status($user_id, $status)
    {
        $user = self::find($user_id);
        $user->status = $status;
        $user->save();
    }

    public function upload_image($user_id, $image)
    {
        if($image == "") {
            return '';
        }
        $dir = 'img/demo/avatars';
        $name = $image->store($dir);
        $user = self::find($user_id);
        $user->image = $name;
        $user->save();
    }

    public function delete_image($user_id)
    {
        Storage::delete(self::find($user_id)->image);
    }

    public function add_links($user_id, $vk, $telegram, $instagram)
    {
        $user = self::find($user_id);
        $user->vk = $vk;
        $user->telegram = $telegram;
        $user->instagram = $instagram;
        $user->save();
    }

    public function free_email($user_id, $email)
    {
        $user = self::find($user_id);
        $check = self::where('email', $email)->first();
        if(!$check || $email == $user->email) {
            return true;
        }
        return false;
    }

    public function edit_security($user_id, $email, $password)
    {
        $user = self::find($user_id);
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();
    }

    public function deleteUser($user_id)
    { 
        $user = self::find($user_id);
        $user->delete_image($user_id);
        $user->delete();
    }
}
