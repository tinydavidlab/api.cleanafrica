<?php


namespace App\Traits;


use App\Models\Admin;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait TicketRelations
{
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
     * Admin relationship.
     *
     * @return BelongsTo
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo( Admin::class );
    }
}
