<?php


namespace App\Traits;


use App\Models\Admin;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    /**
     * Category relationship.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo( Category::class );
    }
}
