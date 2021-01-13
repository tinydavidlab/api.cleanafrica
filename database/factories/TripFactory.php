<?php

namespace Database\Factories;

use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TripFactory extends Factory
{
    protected $model = Trip::class;

    public function definition(): array
    {
        return [
            'customer_name' => $this->faker->name,
            'customer_primary_phone_number' => $this->faker->phoneNumber,
            'customer_secondary_phone_number' => $this->faker->phoneNumber,
            'customer_apartment_number' => $this->faker->randomNumber(),
            'customer_country' => $this->faker->randomNumber(),
            'customer_division' => $this->faker->randomNumber(),
            'customer_subdivision' => $this->faker->randomNumber(),
            'customer_snoocode' => '4BN-8OC',
            'delivery_status' => 'pending',
            'collection_date' => Carbon::parse( '+4 day' ),

            'collector_country' => $this->faker->country,
            'collector_division' => $this->faker->state,
            'collector_subdivision' => $this->faker->city,

            'photo_1' => $this->faker->image(),
            'photo_2' => $this->faker->image(),

            'collector_date' => Carbon::parse( '+3 day' ),
            'collector_time' => $this->faker->time(),
            'collector_signature' => Str::random( 9 ),
        ];
    }
}
