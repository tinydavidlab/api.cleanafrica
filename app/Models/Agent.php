<?php

namespace App\Models;

use App\Traits\AgentRelations;
use App\Traits\JWTProps;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Agent extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable,
        Authorizable,
        JWTProps,
        AgentRelations,
        HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'activated_at',
        'type',
        'company_id',
        'device_id',
        'password'
    ];

    protected $dates = [ 'activated_at' ];
}
