<?php


namespace App\Traits;


use App\Models\Category;
use App\Models\Customer;
use App\Models\Feedback;
use App\Models\Ticket;
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

    /**
     * Tickets relationship.
     *
     * @return HasManyThrough
     */
    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough( Ticket::class, Customer::class );
    }

    /**
     * Category relationship.
     *
     * @return HasMany
     */
    public function categories(): HasMany
    {
        return $this->hasMany( Category::class );
    }
}
