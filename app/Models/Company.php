<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'email',
        'tagline',
        'website',
        'phone_number',
        'activated_at'
    ];

    protected $dates = [ 'activated_at' ];

    public function getIsActivatedAttribute(): bool
    {
        return !is_null( $this->getAttribute( 'activated' ) );
    }

    /**
     * Customer relationship.
     *
     * @return HasMany
     */
    public function customers(): HasMany
    {
        return $this->hasMany( Customer::class );
    }

    /**
     * Trip relationship.
     *
     * @return HasMany
     */
    public function trips(): HasMany
    {
        return $this->hasMany( Trip::class );
    }
}
