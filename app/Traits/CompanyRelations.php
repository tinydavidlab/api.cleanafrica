<?php


namespace App\Traits;


use App\Models\Customer;
use App\Models\Feedback;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

trait CompanyRelations
{
    /**
     * Customer relationship.
     *
     * @return HasMany
     */
    public function customers(): HasMany
    {
        return $this->hasMany( Customer::class );
    }

    /**
     * Trip relationship.
     *
     * @return HasMany
     */
    public function trips(): HasMany
    {
        return $this->hasMany( Trip::class );
    }

    /**
     * Feedback relationship.
     *
     * @return HasManyThrough
     */
    public function feedback(): HasManyThrough
    {
        return $this->hasManyThrough( Feedback::class, Customer::class );
    }
}
