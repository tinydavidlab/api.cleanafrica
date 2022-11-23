<?php


namespace App\Repositories;


use Prettus\Repository\Eloquent\BaseRepository;

class AnnouncementRepository extends BaseRepository
{

    public function model(): string
    {
        return 'App\Models\Announcement';
    }

    public function getForCompany( int $company_id )
    {
        return $this->findWhere(
            [ 'company_id' => $company_id ]
        );
    }
}
