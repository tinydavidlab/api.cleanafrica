<?php


use App\Models\Customer;

class CustomerFeatureTest extends TestCase
{
    /** @test */
    public function a_customer_can_register()
    {
        $customer = Customer::factory()->make();
        $response = $this->post( '/login', $customer->toArray() );
        dd( $response );
    }
}
