<?php


namespace App\Repositories;


use Prettus\Repository\Eloquent\BaseRepository;

class CompanyRepository extends BaseRepository
{
    public function model(): string
    {
        return 'App\Models\Company';
    }
}
