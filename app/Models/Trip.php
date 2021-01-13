<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_name',
        'customer_primary_phone_number',
        'customer_secondary_phone_number',
        'customer_apartment_number',
        'customer_country',
        'customer_division',
        'customer_subdivision',
        'customer_snoocode',
        'collector_country',
        'collector_division',
        'collector_subdivision',
        'collector_snoocode',
        'photo_1',
        'photo_2',
        'collector_date',
        'collector_time',
        'collector_signature',
        'company_id',
        'delivery_status',
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
}
