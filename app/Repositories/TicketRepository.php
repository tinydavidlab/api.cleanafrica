<?php


namespace App\Repositories;


use Prettus\Repository\Eloquent\BaseRepository;

class TicketRepository extends BaseRepository
{
    public function model(): string
    {
        return 'App\Models\Ticket';
    }

    public function getCountOfTickets(  )
    {
        return $this->count();
    }

    public function getCountOfTicketsPerStatus( $status )
    {
        return $this->count(['status' => $status]);
    }


}
