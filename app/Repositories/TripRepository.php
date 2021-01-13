<?php


namespace App\Repositories;


use Prettus\Repository\Eloquent\BaseRepository;

class TripRepository extends BaseRepository
{
    public function model(): string
    {
        return 'App\Models\Trip';
    }

    public function getForCompany( int $id )
    {
        return $this->findWhere( [
            'company_id' => $id
        ] );
    }
}
