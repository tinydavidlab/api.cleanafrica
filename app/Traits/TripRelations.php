<?php


namespace App\Traits;


use App\Models\Agent;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Truck;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait TripRelations
{
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
     * Truck relationship.
     *
     * @return BelongsTo
     */
    public function truck(): BelongsTo
    {
        return $this->belongsTo( Truck::class );
    }

    /**
     * Customer relationship.
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo( Customer::class );
    }

    /**
     * Collector relationship.
     *
     * @return BelongsTo
     */
    public function collector(): BelongsTo
    {
        return $this->belongsTo( Agent::class, 'collector_id' );
    }
}
