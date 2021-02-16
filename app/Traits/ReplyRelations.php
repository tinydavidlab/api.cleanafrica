<?php


namespace App\Traits;


use App\Models\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
     * Admin|User relationship.
     *
     * @return MorphTo
     */
    public function replyable(): MorphTo
    {
        return $this->morphTo();
    }
}
