<?php


namespace App\Traits;


use App\Models\Ticket;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

trait ReplyRelations
{
    /**
     * Admin|User relationship.
     *
     * @return MorphTo
     */
    public function replyable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Ticket relationship.
     *
     * @return BelongsTo
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo( Ticket::class );
    }
}
