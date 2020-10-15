<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Todo extends Model
{
    protected $fillable = [
        'user_id', 'title', 'isCompleted', 'token'
    ];

    public function users(){
        return $this->belongsTo(User::class);
    }

    public function subTodos(){
        return $this->hasMany(SubTodo::class);
    }

    public function createTodo($request){
        $this->user_id = Auth::user()->id;
        $this->title = $request->title;
        $this->token = Str::random(15);
        $this->save();
        return $this;
    }
}
