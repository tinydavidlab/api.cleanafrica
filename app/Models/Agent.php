<?php

namespace App\Models;

use App\Traits\AgentProps;
use App\Traits\AgentRelations;
use App\Traits\AuthPassportTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;

class Agent extends Model implements AuthenticatableContract,
                                     AuthorizableContract
{
    use Authenticatable,
        Authorizable,
        HasApiTokens,
        AuthPassportTrait,
        Notifiable,
        AgentRelations,
        AgentProps,
        HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'activated_at',
        'type',
        'company_id',
        'can_optimise',
        'device_id',
        'password',
        'device_token'
    ];

    protected $dates = [ 'activated_at' ];
}
