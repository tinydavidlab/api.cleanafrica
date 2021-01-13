<?php


namespace App\Repositories;


use Prettus\Repository\Eloquent\BaseRepository;

class FeedbackRepository extends BaseRepository
{
    public function model(): string
    {
        return 'App\Models\Feedback';
    }

    public function getForCustomer( int $id )
    {
        return $this->findWhere( [
            'customer_id' => $id
        ] );
    }
}
