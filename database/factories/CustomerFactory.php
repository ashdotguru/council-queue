<?php

namespace Database\Factories;

use App\Models\ {
    Citizen,
    Customer,
    Organisation,
    Service
};
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
    public function definition()
    {
        $types = collect([Citizen::class, Organisation::class]);
        $type = $types->random();

        return [
            'service_id' => Service::select('id')
                ->inRandomOrder()
                ->first()
                ->id,
            'customer_type' => $type,
            'customer_id' => $type::factory(),
            'queued_at' => now(),
        ];
    }
}
