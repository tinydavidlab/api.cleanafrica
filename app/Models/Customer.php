<?php

namespace App\Models;

use App\Traits\CustomerProps;
use App\Traits\CustomerRelations;
use App\Traits\JWTProps;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Customer extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable,
        Authorizable,
        HasFactory,
        Notifiable,
        CustomerProps,
        CustomerRelations,
        JWTProps;

    protected $fillable = [
        'name',
        'customer_id',
        'address',
        'snoocode',
        'latitude',
        'longitude',
        'division',
        'subdivision',
        'country',
        'phone_number',
        'password',
        'property_photo',
        'device_id',
        'apartment_number',
        'company_id',
        'device_token'
    ];
}
