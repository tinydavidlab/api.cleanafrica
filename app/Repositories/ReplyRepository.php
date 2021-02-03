<?php


namespace App\Repositories;


use Prettus\Repository\Eloquent\BaseRepository;

class ReplyRepository extends BaseRepository
{
    public function model(): string
    {
        return 'App\Models\Reply';
    }

    public function getForTicket( int $ticketId )
    {
        return $this->findWhere( [ 'ticket_id' => $ticketId ] );
    }
}
