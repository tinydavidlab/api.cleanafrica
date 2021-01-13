<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'address' => $this->faker->unique()->streetAddress,
            'snoocode' => '4BN-7MM',
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'division' => $this->faker->state,
            'subdivision' => $this->faker->city,
            'country' => $this->faker->country,
            'phone_number' => $this->faker->phoneNumber,
            'property_photo' => $this->faker->imageUrl(),
        ];
    }
}
