<?php


namespace App\Repositories;


use Prettus\Repository\Eloquent\BaseRepository;

class CustomerRepository extends BaseRepository
{
    public function model(): string
    {
        return 'App\Models\Customer';
    }

    public function getForCompany( int $company_id )
    {
        return $this->findWhere( [ 'company_id' => $company_id ] );
    }

    public function findForCompany( int $id, int $customer_id )
    {
        return $this->findWhere( [
            'company_id' => $id,
            'id' => $customer_id
        ] )->first();
    }

    public function countCustomersForCompany(int $company_id)
    {
        return $this->count(['company_id' => $company_id]);
    }

    public function countAllCustomers()
    {
        return $this->count();
    }
}
