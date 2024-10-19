<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Yantrana\Components\Subvendor\Model\SubVendorSubscription;
use Illuminate\Database\Seeder;

class SubVendorSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubVendorSubscription::truncate();
        $now = now();
        $data = [
            ['name' => 'Free', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Standard', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Premium', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Enterprise', 'created_at' => $now, 'updated_at' => $now]
        ];
        SubVendorSubscription::insert($data);
    }
}
