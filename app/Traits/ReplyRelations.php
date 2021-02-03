<?php


namespace App\Traits;


use App\Models\Agent;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait ReplyRelations
{
    /**
     * Customer relatoionship.
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo( Customer::class );
    }

    /**
     * Agent relationship.
     *
     * @return BelongsTo
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo( Agent::class );
    }
}
