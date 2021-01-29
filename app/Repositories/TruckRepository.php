<?php


namespace App\Repositories;


use Prettus\Repository\Eloquent\BaseRepository;

class TruckRepository extends BaseRepository
{
    public function model(): string
    {
        return 'App\Models\Truck';
    }

    public function getForCompany( int $id )
    {
        return $this->findWhere( [
            'company_id' => $id
        ] );
    }

    public function countCompanyTrucks(int $id)
    {
        return $this->count([
            'company_id' => $id
        ]);
    }
}
