<?php


namespace App\Traits;


use App\Models\Customer;
use App\Models\Feedback;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * @return HasMany
     */
    public function feedback(): HasMany
    {
        return $this->hasMany( Feedback::class );
    }
}
