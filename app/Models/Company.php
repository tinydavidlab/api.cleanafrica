<?php

namespace App\Models;

use App\Traits\CompanyProps;
use App\Traits\CompanyRelations;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use CompanyRelations,
        CompanyProps;

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

    protected $withCount = [ 'tickets' ];

    protected static function boot()
    {
        parent::boot();
        static::created( function ( $company ) {
            $company->categories()->createMany( [
                [ 'name' => 'Complaints', 'type' => 'support' ],
                [ 'name' => 'Feedback', 'type' => 'support' ],
            ] );
        } );
    }
}
