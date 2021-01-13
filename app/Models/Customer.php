<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Customer extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable,
        Authorizable,
        HasFactory;

    protected $fillable = [
        'name',
        'address',
        'snoocode',
        'latitude',
        'longitude',
        'division',
        'subdivision',
        'country',
        'phone_number',
        'property_photo',
        'device_id'
    ];
}
