<?php


namespace App\Filters;


use Carbon\Carbon;

class TripFilter extends QueryFilter
{
    public function filter($filter)
    {
        if ($filter == 'all') {
            return $this->builder
                ->orderBy('created_at', 'desc')
                ->get();
        }

        if ($filter = 'by_date') {
            $today = Carbon::now()->format('Y-m-d');
            return $this->builder
                ->where('collector_date', $today)
                ->orderBy('created_at', 'desc')
                ->get();
        }
    }
}
