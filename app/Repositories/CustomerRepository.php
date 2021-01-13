<?php


namespace App\Repositories;


use Prettus\Repository\Eloquent\BaseRepository;

class CustomerRepository extends BaseRepository
{
    public function model(): string
    {
        return 'App\Models\Customer';
    }
}
