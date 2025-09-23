<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebUser extends Model implements AuthenticatableContract

{
    use HasFactory, Authenticatable;

    protected $table = 'web_users';
    protected $primaryKey = 'id';
    protected $hidden = ['password','_token'];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'password'
    ];
}
