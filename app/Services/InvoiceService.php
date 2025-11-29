<?php

namespace App\Services;

class InvoiceService
{
    public static function getServices()
    {
        return [
            ['id' => 1, 'name_ar' => 'زيت المحرك / فلتر الزيت', 'name_en' => 'Engine Oil/Oil Filter'],
            ['id' => 2, 'name_ar' => 'زيت الجير / فلتر الزيت', 'name_en' => 'Gear Oil/Gear Oil filter'],
            ['id' => 3, 'name_ar' => 'زيت الدفريشن', 'name_en' => 'Differential Oil Change'],
            ['id' => 4, 'name_ar' => 'تنظيف / تلحيم الراديتور', 'name_en' => 'Radiator Welding / Cleaning'],
            ['id' => 5, 'name_ar' => 'تركيب / خدمة الراديتور الجديد', 'name_en' => 'New Radiator / Service'],
            ['id' => 6, 'name_ar' => 'تغير طوق الراديتور', 'name_en' => 'Radiator Cover Change'],
            ['id' => 7, 'name_ar' => 'خدمة الكوندنسر (جديد / قديم)', 'name_en' => 'Condenser (New / Old) Service'],
            ['id' => 8, 'name_ar' => 'بطارية', 'name_en' => 'Battery'],
            ['id' => 9, 'name_ar' => 'خدمة كومبرسور التكيف (جديد / قديم)', 'name_en' => 'AC Compressor (New / Old Labor)'],
            ['id' => 10, 'name_ar' => 'خدمة فك طبلون السيارة', 'name_en' => 'Dashboard Service'],
            ['id' => 11, 'name_ar' => 'خدمة المبخر التكيف', 'name_en' => 'AC Evaporator Service'],
            ['id' => 12, 'name_ar' => 'خدمة صمام التمدد التكيف', 'name_en' => 'AC Expansion Valve'],
            ['id' => 13, 'name_ar' => 'غاز التكيف / زيت الكومبرسور', 'name_en' => 'AC Gas / Compressor Oil'],
            ['id' => 14, 'name_ar' => 'تغير خرطوم التكيف / تلحيم الخرطوم', 'name_en' => 'AC Hose Change / Welding'],
            ['id' => 15, 'name_ar' => 'تغير خرطوم الباور ستيرنج', 'name_en' => 'Power Steering Hose Change'],
            ['id' => 16, 'name_ar' => 'تغير فلتر التكيف', 'name_en' => 'AC Cabin Filter Change'],
            ['id' => 17, 'name_ar' => 'خدمة تلميع الديسك', 'name_en' => 'Disc Polish Service'],
            ['id' => 18, 'name_ar' => 'خدمة الكومبرسور', 'name_en' => 'Compressor Service'],
            ['id' => 19, 'name_ar' => 'خدمة دينامو السيارة', 'name_en' => 'Alternator Service'],
            ['id' => 20, 'name_ar' => 'خدمة موتور التشغيل', 'name_en' => 'Starter Motor Service'],
            ['id' => 21, 'name_ar' => 'خدمة مراوح السيارة', 'name_en' => 'Fan Motor Service'],
            ['id' => 22, 'name_ar' => 'تغير سير السيارة', 'name_en' => 'Belt Change'],
            ['id' => 23, 'name_ar' => 'خدمة لمبات الإضاءة', 'name_en' => 'Bulb Change'],
            ['id' => 24, 'name_ar' => 'خدمة الروابط / البوش جونيت', 'name_en' => 'Lower / Upper Arm bush Service'],
            ['id' => 25, 'name_ar' => 'خدمة للاكسل / سي', 'name_en' => 'Link rod / Tie end rod / Ball joint Service'],
            ['id' => 26, 'name_ar' => 'خدمة مشخة البترول / خزان البترول', 'name_en' => 'Axle / CV joint Service'],
            ['id' => 27, 'name_ar' => 'خدمة طرمبة البنزين / خزان البنزين', 'name_en' => 'Fuel Pump / Tank Service'],
            ['id' => 28, 'name_ar' => 'خدمة جاسكيت الكبلينة', 'name_en' => 'Engine Gasket'],
            ['id' => 29, 'name_ar' => 'خدمة تركيب دبة السيارة العادية / الإلكترونية', 'name_en' => 'Manifold Service'],
            ['id' => 30, 'name_ar' => 'خدمة دبة البيئة', 'name_en' => 'Catalytic Service'],
            ['id' => 31, 'name_ar' => 'خدمة جاسكيت العادم', 'name_en' => 'Exhaust Gasket Service'],
            ['id' => 32, 'name_ar' => 'تركيب البيرنج', 'name_en' => 'Bearing Installation'],
            ['id' => 33, 'name_ar' => 'شمعات الاحتراق / كويلات / الثروتل بودي', 'name_en' => 'Plug / Throttle / Coil Service'],
            ['id' => 34, 'name_ar' => 'تلحيم الجنوط', 'name_en' => 'Wheel Welding'],
            ['id' => 35, 'name_ar' => 'تلحيم متنفرق', 'name_en' => 'Miscellaneous Welding'],
            ['id' => 36, 'name_ar' => 'رسوم شغل ميكانيكي خارجي', 'name_en' => 'Worker outside Labor'],
            ['id' => 37, 'name_ar' => 'رسوم شغل ميكانيكي عام', 'name_en' => 'General Mechanical Labor'],
            ['id' => 38, 'name_ar' => 'رسوم شغل كهربائي عام', 'name_en' => 'General Electrical Labor'],
            ['id' => 39, 'name_ar' => 'خدمة أخرى', 'name_en' => 'Others']
        ];
    }
}
