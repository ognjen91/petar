<?php

namespace Database\Factories;

use App\Models\ExcursionType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExcursionTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExcursionType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => ['regular', 'private'][rand(0,1)],
            'slug' => $this->faker->word,
            'name' => $this->faker->word . " " . $this->faker->word
        ];
    }
}
