<?php

namespace App\Models;

use App\Traits\CustomerProps;
use App\Traits\JWTProps;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Customer extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable,
        Authorizable,
        HasFactory,
        CustomerProps,
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
        'company_id'
    ];

    /**
     * Company relationship.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo( Company::class );
    }

    /**
     * Ticket relationship.
     *
     * @return HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany( Ticket::class );
    }

    public function getLinkAttribute(): string
    {
        $latitude  = $this->getAttribute( 'latitude' );
        $longitude = $this->getAttribute( 'longitude' );

        return "http://maps.google.com/maps?q={$latitude},{$longitude}";
    }
}
