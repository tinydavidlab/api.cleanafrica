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
        return $this->findWhereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    }

    public function getAllTripsForThisMonth()
    {
        return $this->findWhereBetween( 'created_at', [ Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth() ] );
    }

    public function getAllTripsForThisYear()
    {
        return $this->findWhereBetween( 'created_at', [ Carbon::now()->startOfYear(), Carbon::now()->endOfYear() ] );
    }

    public function getAllTripsForUser()
    {
        return $this->findWhere( [ 'customer_id' => auth()->id() ] );
    }

    /** ======== Statistics ========* */


    public function countCompanyTripsPerStatusPerDate( int $id, $status, $date ): int
    {
        return $this->count( [
            'company_id'      => $id,
            'delivery_status' => $status,
            'collector_date'  => $date
        ] );
    }

    public function countCompanyTripsForToday( int $id, $date ): int
    {
        return $this->count( [
            'company_id'     => $id,
            'collector_date' => $date
        ] );
    }

    public function countAllTrips(): int
    {
        return $this->count();
    }

    public function countAllTripsByStatus( $status ): int
    {
        return $this->count( [
            'delivery_status' => $status
        ] );
    }

    public function countAllTripsByStatusAndDate( $status, $date ): int
    {
        return $this->count( [
            'delivery_status' => $status,
            'collector_date'  => $date
        ] );
    }

    public function countAllTripsForToday( $date ): int
    {
        return $this->count( [
            'collector_date' => $date
        ] );
    }

    public function countTotalTripsForTheWeek(): int
    {
        return $this->findWhereBetween( 'created_at', [ Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek() ] )->count();
    }

    public function countTotalTripsForTheMonth(): int
    {
        return $this->findWhereBetween( 'created_at', [ Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth() ] )->count();
    }

    public function countTotalTripsForThisYear(): int
    {
        return $this->findWhereBetween( 'created_at', [ Carbon::now()->startOfYear(), Carbon::now()->endOfYear() ] )->count();
    }


}
