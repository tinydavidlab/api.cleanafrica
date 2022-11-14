<?php

namespace App\Models;

use App\Traits\AdminProps;
use App\Traits\AdminRelations;
use App\Traits\AuthPassportTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;

class Admin extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable,
        Authorizable,
        HasApiTokens,
        AuthPassportTrait,
        Notifiable,
        AdminRelations,
        AdminProps,
        HasFactory;

    protected $fillable = [
        'name',
        'type',
        'phone_number',
        'password',
        'company_id',
        'device_token'
    ];

    protected $hidden = [ 'password' ];
}
