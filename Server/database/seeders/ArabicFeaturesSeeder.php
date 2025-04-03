<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feature;

class ArabicFeaturesSeeder extends Seeder
{
    // Run the database seeds.
    public function run()
    {
        $features = [
            // Land Features (feature_group_id: 1)
            ['name' => 'بالقرب من الطريق السريع', 'feature_group_id' => 1],
            ['name' => 'مُسيَّج', 'feature_group_id' => 1],
            ['name' => 'أرض زراعية', 'feature_group_id' => 1],
            ['name' => 'إطلالة جبلية', 'feature_group_id' => 1],
            ['name' => 'إطلالة بحرية', 'feature_group_id' => 1],
        
            // Location Features (feature_group_id: 2)
            ['name' => 'بالقرب من المدرسة', 'feature_group_id' => 2],
            ['name' => 'بالقرب من المستشفى', 'feature_group_id' => 2],
            ['name' => 'بالقرب من المول التجاري', 'feature_group_id' => 2],
            ['name' => 'بالقرب من محطة المترو', 'feature_group_id' => 2],
            ['name' => 'بالقرب من المنتزه', 'feature_group_id' => 2],
        
            // House Amenities (feature_group_id: 3)
            ['name' => 'حمام سباحة', 'feature_group_id' => 3],
            ['name' => 'كراج', 'feature_group_id' => 3],
            ['name' => 'حديقة خاصة', 'feature_group_id' => 3],
            ['name' => 'شرفة واسعة', 'feature_group_id' => 3],
            ['name' => 'نظام أمن متكامل', 'feature_group_id' => 3],
        
            // Building Features (feature_group_id: 4)
            ['name' => 'تدفئة مركزية', 'feature_group_id' => 4],
            ['name' => 'منزل ذكي', 'feature_group_id' => 4],
            ['name' => 'نظام طاقة شمسية', 'feature_group_id' => 4],
            ['name' => 'مصعد كهربائي', 'feature_group_id' => 4],
            ['name' => 'أبواب مضادة للحريق', 'feature_group_id' => 4],
        
            // Car Features (feature_group_id: 5)
            ['name' => 'فتحة سقف', 'feature_group_id' => 5],
            ['name' => 'مقاعد جلدية', 'feature_group_id' => 5],
            ['name' => 'نظام صوتي فاخر', 'feature_group_id' => 5],
            ['name' => 'كاميرا خلفية', 'feature_group_id' => 5],
            ['name' => 'إضاءة داخلية LED', 'feature_group_id' => 5],
        
            // Safety Features (feature_group_id: 6)
            ['name' => 'نظام ABS', 'feature_group_id' => 6],
            ['name' => 'وسائد هوائية', 'feature_group_id' => 6],
            ['name' => 'نظام تحذير النقطة العمياء', 'feature_group_id' => 6],
            ['name' => 'مكابح تلقائية للطوارئ', 'feature_group_id' => 6],
            ['name' => 'مساعد البقاء في المسار', 'feature_group_id' => 6],
        
            // Marine Equipment (feature_group_id: 7)
            ['name' => 'معدات صيد', 'feature_group_id' => 7],
            ['name' => 'مطبخ', 'feature_group_id' => 7],
            ['name' => 'نظام تحلية مياه', 'feature_group_id' => 7],
            ['name' => 'راديو بحري', 'feature_group_id' => 7],
            ['name' => 'مظلة ضد الشمس', 'feature_group_id' => 7],
        
            // Navigation Features (feature_group_id: 8)
            ['name' => 'نظام GPS', 'feature_group_id' => 8],
            ['name' => 'رادار', 'feature_group_id' => 8],
            ['name' => 'بوصلة إلكترونية', 'feature_group_id' => 8],
            ['name' => 'مساعد القيادة الآلي', 'feature_group_id' => 8],
            ['name' => 'حساسات كشف العوائق', 'feature_group_id' => 8],
        
            // Motorcycle Features (feature_group_id: 9)
            ['name' => 'عادم مخصص', 'feature_group_id' => 9],
            ['name' => 'مقابض مدفأة', 'feature_group_id' => 9],
            ['name' => 'إطارات مقاومة للانزلاق', 'feature_group_id' => 9],
            ['name' => 'مساعد قيادة كهربائي', 'feature_group_id' => 9],
            ['name' => 'مقعد مريح', 'feature_group_id' => 9],
        
            // Performance Features (feature_group_id: 10)
            ['name' => 'نظام ABS', 'feature_group_id' => 10],
            ['name' => 'نظام التحكم في الجر', 'feature_group_id' => 10],
            ['name' => 'محرك عالي الأداء', 'feature_group_id' => 10],
            ['name' => 'نظام تعليق رياضي', 'feature_group_id' => 10],
            ['name' => 'مكابح رياضية', 'feature_group_id' => 10],
        ];        
        
        foreach ($features as $feature) {
            Feature::create($feature);
        }
    }
}
