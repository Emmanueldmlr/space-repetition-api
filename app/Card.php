<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Card extends Model
{
    use Uuid;
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
    protected $fillable = [
        'uuid', 'title', 'body', 'tags', 'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getCards(){
        return Card::where('user_id', Auth::user()->id)->get();
    }

    public function createCard(){
        $this->user_id = Auth::user()->id;
        $this->save();
        return $this;
    }

    public function fetchCard($uuid){
        return Card::where(['user_id' => Auth::user()->id, 'uuid' => $uuid])->first();
    }
}
