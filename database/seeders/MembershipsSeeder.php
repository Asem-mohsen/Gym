<?php

namespace Database\Seeders;

use App\Models\Membership;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipsSeeder extends Seeder
{

    public function run(): void
    {
        Membership::insert([
            [
                'name' => json_encode(['en' => 'Basic', 'ar' => 'أساسي']),
                'period' => '1 Month',
                'description' => json_encode(['en' => 'Basic membership description', 'ar' => 'وصف العضوية الأساسية']),
                'price' => 30.00,
                'status' => 1,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => json_encode(['en' => 'Standard', 'ar' => 'قياسي']),
                'period' => '3 Months',
                'description' => json_encode(['en' => 'Standard membership description', 'ar' => 'وصف العضوية القياسية']),
                'price' => 75.00,
                'status' => 1,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => json_encode(['en' => 'VIP', 'ar' => 'امتياز']),
                'period' => '3 Months',
                'description' => json_encode(['en' => 'VIP membership description', 'ar' => 'وصف العضوية القياسية']),
                'price' => 375.00,
                'status' => 1,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
