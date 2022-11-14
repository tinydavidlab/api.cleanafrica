<?php


namespace App;


use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

trait CanFilter
{
    /**
     * Handling filtering on model records.
     *
     * @param Builder $query
     * @param QueryFilter $filter
     *
     * @return Builder
     */
    public function scopeFilter( Builder $query, QueryFilter $filter )
    {
        return $filter->apply( $query );
    }

}
