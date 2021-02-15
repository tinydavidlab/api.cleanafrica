<?php


namespace App\Traits;


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

    public function replyable()
    {
        return $this->morphTo();
    }
}
