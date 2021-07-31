<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = collect([
            'Housing',
            'Benefits',
            'Council Tax',
            'Fly-tipping',
            'Missed Bin',
        ]);

        $insert = $services->map(function ($service) {
            return ['name' => $service];
        });

        Service::insert($insert->toArray());
    }
}
