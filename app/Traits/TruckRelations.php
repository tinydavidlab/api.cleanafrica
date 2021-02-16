<?php


namespace App\Traits;


use App\Models\Agent;
use App\Models\Company;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait TruckRelations
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

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    public function agents():BelongsToMany
    {
       return $this->belongsToMany(Agent::class, 'trucks_agents','truck_id','agent_id')->withTimestamps();
    }

}
