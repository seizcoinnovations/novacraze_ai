<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Yantrana\Components\Subvendor\Model\Category;
use App\Yantrana\Components\SubvendorCompanyCategories\Models\CompanyCategory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanyCategory::truncate();
        $now = now();
        $data = [
            ['name' => 'pharmacy', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'bakery', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'cloth store', 'created_at' => $now, 'updated_at' => $now]
        ];
        CompanyCategory::insert($data);
    }
}
