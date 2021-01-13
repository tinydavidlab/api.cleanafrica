<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agent extends Model
{
    protected $fillable = [
        'name',
        'phone_number',
        'activated_at',
        'type',
        'company_id',
        'device_id'
    ];

    protected $dates = [ 'activated_at' ];

    /**
     * Company relationship.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo( Company::class );
    }
}
