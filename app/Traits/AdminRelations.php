<?php


namespace App\Traits;


use App\Models\Company;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait AdminRelations
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
}
