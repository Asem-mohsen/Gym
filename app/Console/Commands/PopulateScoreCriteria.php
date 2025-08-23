<?php

namespace App\Console\Commands;

use App\Models\ScoreCriteria;
use Illuminate\Console\Command;

class PopulateScoreCriteria extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'score:populate-criteria';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate initial score criteria for gym branches';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Populating score criteria...');

        $criteria = [
            [
                'name' => ['en' => 'TV Screens', 'ar' => 'شاشات تلفاز'],
                'description' => ['en' => 'Multiple TV screens for entertainment', 'ar' => 'شاشات تلفاز متعددة للترفيه'],
                'points' => 1,
                'is_negative' => false,
                'sort_order' => 1,
            ],
            [
                'name' => ['en' => 'Swimming Pool', 'ar' => 'مسبح'],
                'description' => ['en' => 'Clean and well-maintained swimming pool', 'ar' => 'مسبح نظيف ومحافظ عليه'],
                'points' => 2,
                'is_negative' => false,
                'sort_order' => 2,
            ],
            [
                'name' => ['en' => 'Air Conditioning', 'ar' => 'تكييف هواء'],
                'description' => ['en' => 'Proper air conditioning system', 'ar' => 'نظام تكييف هواء مناسب'],
                'points' => 1,
                'is_negative' => false,
                'sort_order' => 3,
            ],
            [
                'name' => ['en' => 'Modern Equipment', 'ar' => 'معدات حديثة'],
                'description' => ['en' => 'State-of-the-art fitness equipment', 'ar' => 'معدات لياقة بدنية متطورة'],
                'points' => 3,
                'is_negative' => false,
                'sort_order' => 4,
            ],
            [
                'name' => ['en' => 'Personal Trainers', 'ar' => 'مدربين شخصيين'],
                'description' => ['en' => 'Certified personal trainers available', 'ar' => 'مدربين شخصيين معتمدين متاحين'],
                'points' => 2,
                'is_negative' => false,
                'sort_order' => 5,
            ],
            [
                'name' => ['en' => 'Group Classes', 'ar' => 'فصول جماعية'],
                'description' => ['en' => 'Variety of group fitness classes', 'ar' => 'تنوع في فصول اللياقة الجماعية'],
                'points' => 2,
                'is_negative' => false,
                'sort_order' => 6,
            ],
            [
                'name' => ['en' => 'Locker Rooms', 'ar' => 'غرف تبديل الملابس'],
                'description' => ['en' => 'Clean and spacious locker rooms', 'ar' => 'غرف تبديل ملابس نظيفة وواسعة'],
                'points' => 1,
                'is_negative' => false,
                'sort_order' => 7,
            ],
            [
                'name' => ['en' => 'Shower Facilities', 'ar' => 'مرافق الاستحمام'],
                'description' => ['en' => 'Clean shower facilities with hot water', 'ar' => 'مرافق استحمام نظيفة مع ماء ساخن'],
                'points' => 1,
                'is_negative' => false,
                'sort_order' => 8,
            ],
            [
                'name' => ['en' => 'Parking Space', 'ar' => 'موقف سيارات'],
                'description' => ['en' => 'Adequate parking space available', 'ar' => 'موقف سيارات كافي متاح'],
                'points' => 1,
                'is_negative' => false,
                'sort_order' => 9,
            ],
            [
                'name' => ['en' => 'WiFi Access', 'ar' => 'وصول للإنترنت'],
                'description' => ['en' => 'Free WiFi access for members', 'ar' => 'وصول مجاني للإنترنت للأعضاء'],
                'points' => 1,
                'is_negative' => false,
                'sort_order' => 10,
            ],
            [
                'name' => ['en' => 'Childcare Services', 'ar' => 'خدمات رعاية الأطفال'],
                'description' => ['en' => 'Childcare services available', 'ar' => 'خدمات رعاية أطفال متاحة'],
                'points' => 2,
                'is_negative' => false,
                'sort_order' => 11,
            ],
            [
                'name' => ['en' => 'Nutrition Consultation', 'ar' => 'استشارة تغذية'],
                'description' => ['en' => 'Nutrition consultation services', 'ar' => 'خدمات استشارة تغذية'],
                'points' => 1,
                'is_negative' => false,
                'sort_order' => 12,
            ],
            [
                'name' => ['en' => '24/7 Access', 'ar' => 'وصول 24/7'],
                'description' => ['en' => '24/7 gym access for members', 'ar' => 'وصول 24/7 للصالة للأعضاء'],
                'points' => 3,
                'is_negative' => false,
                'sort_order' => 13,
            ],
            [
                'name' => ['en' => 'Sauna/Steam Room', 'ar' => 'ساونا/غرفة بخار'],
                'description' => ['en' => 'Sauna or steam room facilities', 'ar' => 'مرافق ساونا أو غرفة بخار'],
                'points' => 2,
                'is_negative' => false,
                'sort_order' => 14,
            ],
            [
                'name' => ['en' => 'Towel Service', 'ar' => 'خدمة المناشف'],
                'description' => ['en' => 'Clean towel service provided', 'ar' => 'خدمة مناشف نظيفة مقدمة'],
                'points' => 1,
                'is_negative' => false,
                'sort_order' => 15,
            ],

            // Negative criteria
            [
                'name' => ['en' => 'Poor Cleanliness', 'ar' => 'نظافة سيئة'],
                'description' => ['en' => 'Poor overall cleanliness standards', 'ar' => 'معايير نظافة عامة سيئة'],
                'points' => -2,
                'is_negative' => true,
                'sort_order' => 16,
            ],
            [
                'name' => ['en' => 'Equipment Maintenance', 'ar' => 'صيانة المعدات'],
                'description' => ['en' => 'Poor equipment maintenance', 'ar' => 'صيانة سيئة للمعدات'],
                'points' => -2,
                'is_negative' => true,
                'sort_order' => 17,
            ],
            [
                'name' => ['en' => 'Staff Unfriendliness', 'ar' => 'عدم ودية الموظفين'],
                'description' => ['en' => 'Unfriendly or unhelpful staff', 'ar' => 'موظفين غير ودودين أو غير مساعدين'],
                'points' => -1,
                'is_negative' => true,
                'sort_order' => 18,
            ],
            [
                'name' => ['en' => 'Overcrowding', 'ar' => 'ازدحام'],
                'description' => ['en' => 'Consistently overcrowded facilities', 'ar' => 'مرافق مزدحمة باستمرار'],
                'points' => -1,
                'is_negative' => true,
                'sort_order' => 19,
            ],
            [
                'name' => ['en' => 'Poor Ventilation', 'ar' => 'تهوية سيئة'],
                'description' => ['en' => 'Poor air circulation and ventilation', 'ar' => 'دوران هواء وتهوية سيئة'],
                'points' => -1,
                'is_negative' => true,
                'sort_order' => 20,
            ],
        ];

        $created = 0;
        $updated = 0;

        foreach ($criteria as $criterion) {
            $existing = ScoreCriteria::where('name->en', $criterion['name']['en'])->first();
            
            if ($existing) {
                $existing->update($criterion);
                $updated++;
                $this->line("Updated: {$criterion['name']['en']}");
            } else {
                ScoreCriteria::create($criterion);
                $created++;
                $this->line("Created: {$criterion['name']['en']}");
            }
        }

        $this->info("Score criteria population completed!");
        $this->info("Created: {$created} criteria");
        $this->info("Updated: {$updated} criteria");

        return 0;
    }
}
