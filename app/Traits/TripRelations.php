<?php


namespace App\Traits;


use App\Models\Company;
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

    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }


}
