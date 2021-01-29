<?php


namespace App\Repositories;


use Prettus\Repository\Eloquent\BaseRepository;

class AgentRepository extends BaseRepository
{
    public function model(): string
    {
        return 'App\Models\Agent';
    }

    public function getForCompany( int $company_id )
    {
        return $this->findWhere( [ 'company_id' => $company_id ] );
    }

    public function findForCompany( int $id, int $agent_id )
    {
        return $this->findWhere( [
            'company_id' => $id,
            'id' => $agent_id
        ] )->first();
    }

    public function countCollectorsForCompany(int $company_id)
    {
        return $this->count([
            'company_id' => $company_id,
            'type' => 'collector'
        ]);
    }
}
