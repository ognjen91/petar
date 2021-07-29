<?php

namespace Database\Factories;

use App\Models\Excursion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class ExcursionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Excursion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'excursion_type_id' => 1,
            'departure' => $this->faker->dateTimeBetween($startDate = '-15 days', $endDate = 'now'),
            'total_seats' => rand(30,60)
        ];
    }
}
