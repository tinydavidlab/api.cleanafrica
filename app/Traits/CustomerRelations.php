<?php


namespace App\Traits;


use App\Models\Company;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CustomerRelations
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
     * Ticket relationship.
     *
     * @return HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany( Ticket::class );
    }

}
