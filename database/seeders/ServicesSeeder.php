<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{

    public function run(): void
    {
        Service::insert([
            [
                'name' => json_encode(['en' => 'Life Coaching', 'ar' => 'التدريب الحياتي']),
                'description' => json_encode(['en' => 'Description', 'ar' => 'الوصف 1']),
                'duration' => 30,
                'price' => 50.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => json_encode(['en' => 'Diet Doctor', 'ar' => 'تنظيم غذائي']),
                'description' => json_encode(['en' => 'Description 2', 'ar' => 'الوصف 2']),
                'duration' => 45,
                'price' => 75.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
