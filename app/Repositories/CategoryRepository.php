<?php


namespace App\Repositories;


use Prettus\Repository\Eloquent\BaseRepository;

class CategoryRepository extends BaseRepository
{
    public function model(): string
    {
        return 'App\Models\Category';
    }

}
