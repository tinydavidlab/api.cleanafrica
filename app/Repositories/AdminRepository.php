<?php


namespace App\Repositories;


use Prettus\Repository\Eloquent\BaseRepository;

class AdminRepository extends BaseRepository
{
    public function model(): string
    {
        return 'App\Models\Admin';
    }

    public function getForCompany( int $company_id )
    {
        return $this->findWhere( [ 'company_id' => $company_id ] );
    }

}
