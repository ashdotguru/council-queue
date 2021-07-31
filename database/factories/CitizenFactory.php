<?php

namespace Database\Factories;

use App\Models\Citizen;
use Illuminate\Database\Eloquent\Factories\Factory;

class CitizenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Citizen::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $genders = ['male', 'female'];
        $gender = array_rand($genders);

        return [
            'title' => $this->faker->title($gender),
            'first_name' => $this->faker->firstName($gender),
            'last_name' => $this->faker->lastName($gender),
        ];
    }
}
