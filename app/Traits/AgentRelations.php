<?php


namespace App\Traits;


use App\Models\Company;
use App\Models\Truck;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait AgentRelations
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

    public function trucks(): BelongsToMany
    {
        return $this->belongsToMany(Truck::class, 'trucks_agents','agent_id','truck_id');
    }
}
