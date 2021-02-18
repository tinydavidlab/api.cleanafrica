<?php

namespace App\Models;

use App\Traits\AdminProps;
use App\Traits\AdminRelations;
use App\Traits\JWTProps;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable,
        Authorizable,
        JWTProps,
        Notifiable,
        AdminRelations,
        AdminProps,
        HasFactory;

    protected $fillable = [
        'name', 'type', 'phone_number', 'password', 'company_id'
    ];

    protected $hidden = [ 'password' ];
}
