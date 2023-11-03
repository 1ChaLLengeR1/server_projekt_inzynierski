<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    public $timestamps = false;
    protected $table = 'users_table';
    protected $casts = [
        'id'=>'string',
        'username'=>'string',
        'email'=>'string',
        'password'=>'string',
        'type_user'=>'string',
        'remember_me'=>'boolean'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    use HasFactory;

}
