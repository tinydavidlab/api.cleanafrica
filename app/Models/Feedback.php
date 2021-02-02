<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    protected $fillable = [
        'message',
        'photo',
        'customer_id',
        'company_id',
        'device_id',
        'user_agent',
        'app_version',
        'brand',
        'manufacturer',
        'model',
        'stamp'
    ];

    protected $casts = [
        'stamp' => 'array'
    ];

    /**
     * Customer relationship.
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo( Customer::class );
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo( Company::class );
    }
}
