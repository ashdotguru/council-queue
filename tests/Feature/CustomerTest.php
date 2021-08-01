<?php

namespace Tests\Feature;

use App\Models\ {
    Citizen,
    Customer,
    Organisation,
    Service
};
use Database\Seeders\ServicesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Run a specific seeder before each test.
     *
     * @var string
     */
    protected $seeder = ServicesTableSeeder::class;

    /**
     * Test creating customers.
     *
     * @return void
     */
    public function test_customers_can_be_created()
    {
        $customers = Customer::factory()->count(10)->create();

        $this->assertDatabaseCount('customers', 10);
    }

    /**
     * Test a anonymous customer can be stored.
     *
     * @return void
     */
    public function test_anonymous_customer_can_be_stored()
    {
        $response = $this->postJson('/customers', [
            'service' => Service::inRandomOrder()->first()->id,
            'type' => 'anonymous',
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'customer' => [
                    'type' => 'Anonymous',
                ],
            ]);
    }

    /**
     * Test a citizen can be stored.
     *
     * @return void
     */
    public function test_citizen_can_be_stored()
    {
        $citizen = Citizen::factory()->make();

        $response = $this->postJson('/customers', [
            'service' => Service::inRandomOrder()->first()->id,
            'type' => 'citizen',
            'title' => $citizen->title,
            'first_name' => $citizen->first_name,
            'last_name' => $citizen->last_name,
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'customer' => [
                    'type' => 'Citizen',
                ],
            ]);
    }

    /**
     * Test a organisation can be stored.
     *
     * @return void
     */
    public function test_organisation_can_be_stored()
    {
        $organisation = Organisation::factory()->make();

        $response = $this->postJson('/customers', [
            'service' => Service::inRandomOrder()->first()->id,
            'type' => 'organisation',
            'name' => $organisation->name,
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'customer' => [
                    'type' => 'Organisation',
                ],
            ]);
    }

    /**
     * Test a customer can be deleted.
     *
     * @return void
     */
    public function test_customer_can_be_deleted()
    {
        $customer = Customer::factory()->create();

        $response = $this->deleteJson("/customers/{$customer->id}");

        $response->assertStatus(200);
    }
}
