<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    // モデルがその属性以外を持たなくなる
    protected $fillable = ['lesson_id', 'user_id'];
}
