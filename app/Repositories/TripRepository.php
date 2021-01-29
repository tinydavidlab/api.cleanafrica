<?php


namespace App\Repositories;


use App\Models\Trip;
use Prettus\Repository\Eloquent\BaseRepository;

class TripRepository extends BaseRepository
{
    public function model(): string
    {
        return 'App\Models\Trip';
    }

    public function getForCompany(int $id)
    {
        return $this->findWhere([
            'company_id' => $id
        ]);
    }

    public function getTripsForCompany(int $id, $status, $date)
    {
        return $this->findWhere([
            'company_id' => $id,
            'delivery_status' => $status,
            'collector_date' => $date
        ]);
    }

    public function getTripsByStatusForCompany(int $id, $status)
    {
        return $this->findWhere([
            'company_id' => $id,
            'delivery_status' => $status,
        ]);
    }

    public function countTripsPerStatusPerDate(int $id, $status, $date)
    {
        return $this->count([
            'company_id' => $id,
            'delivery_status' => $status,
            'collector_date' => $date
        ]);
    }

    public function countTripsForToday(int $id, $date)
    {
        return $this->count([
            'company_id' => $id,
            'collector_date' => $date
        ]);
    }

    public function filter(\App\Filters\TripFilter $filter)
    {
        return Trip::filter($filter);
    }

}
