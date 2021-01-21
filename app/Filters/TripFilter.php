<?php


namespace App\Filters;


class TripFilter extends QueryFilter
{
    public function filter($filter)
    {
        if ($filter == 'all') {
            return $this->builder->orderBy('created_at', 'desc')->get();
        }
    }
}
