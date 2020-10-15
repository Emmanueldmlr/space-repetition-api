<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SubTodo extends Model
{
    protected $fillable = [
        'todo_id', 'todo', 'token', 'isCompleted'
    ];

    public function todo(){
        return $this->belongsTo(Todo::class, 'todo_id');
    }

    public function createSubTodo($subTodo, $todo_id){
        $this->todo_id = $todo_id;
        $this->todo = $subTodo;
        $this->token = Str::random(15);
        $this->save();
        return $this;
    }

    public function fetchUserTodo($id){
       $todo =  SubTodo::whereHas('todo', function ($query){
            $query->where('user_id', Auth::user()->id);
        })->where('id', $id)->first();

       return $todo;
    }
}
