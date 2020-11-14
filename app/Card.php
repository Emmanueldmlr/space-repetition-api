<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;

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
}
