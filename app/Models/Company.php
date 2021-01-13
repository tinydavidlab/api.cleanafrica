<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
