<?php

namespace Database\Factories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reservation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'booker_id' => 1,
            'excursion_id' => 1,
            'seats' => rand(3,5), 
            'station_id' => 1,
            'price' => [10,20,30,40][rand(0,3)]
        ];
    }
}
