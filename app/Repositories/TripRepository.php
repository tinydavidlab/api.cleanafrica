<?php


namespace App\Repositories;


use App\Models\Trip;
use Carbon\Carbon;
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

    public function getTripsPerDateForCompany(int $id, $date)
    {
        return $this->findWhere([
            'company_id' => $id,
            'collector_date' => $date,
        ]);
    }

    public function getTripsPerDate($date)
    {
        return $this->findWhere([
            'collector_date' => $date,
        ]);
    }

    public function filter(\App\Filters\TripFilter $filter)
    {
        return Trip::filter($filter);
    }

    public function getAllTripsForThisWeek()
    {
        return $this->findWhereBetween('collector_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    }

    public function getAllTripsForThisMonth()
    {
        return $this->findWhereBetween('collector_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
    }

    public function getAllTripsForThisYear()
    {
        return $this->findWhereBetween('collector_date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
    }



    /** ======== Statistics ========* */

    public function countCompanyTripsPerStatusPerDate(int $id, $status, $date)
    {
        return $this->count([
            'company_id' => $id,
            'delivery_status' => $status,
            'collector_date' => $date
        ]);
    }

    public function countCompanyTripsForToday(int $id, $date)
    {
        return $this->count([
            'company_id' => $id,
            'collector_date' => $date
        ]);
    }

    public function countAllTrips()
    {
        return $this->count();
    }

    public function countAllTripsByStatus($status)
    {
        return $this->count([
            'delivery_status' => $status
        ]);
    }

    public function countAllTripsByStatusAndDate($status, $date)
    {
        return $this->count([
            'delivery_status' => $status,
            'collector_date' => $date
        ]);
    }

    public function countAllTripsForToday($date)
    {
        return $this->count([
            'collector_date' => $date
        ]);
    }

    public function countTotalTripsForTheWeek()
    {
        return $this->findWhereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
    }

    public function countTotalTripsForTheMonth()
    {
        return $this->findWhereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();
    }

    public function countTotalTripsForThisYear()
    {
        return $this->findWhereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->count();
    }


}
