<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use  HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname', 'email', 'password', 'token', 'isVerified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function todo (){
        return $this->hasMany(Todo::class);
    }

    public function createUser($request){
        $this->email = $request->email;
        $this->password = bcrypt($request->password);
        $this->nickname = $request->nickname;
        $this->token = Str::random(15);
        $this->save();
        return $this;
    }

    public function fetchUser($token){
        $user = User::where('token', $token)->first();
        return $user;
    }

    public function checkUser($email){
        $user = User::where('email', $email)->first();
        return $user;
    }
}
