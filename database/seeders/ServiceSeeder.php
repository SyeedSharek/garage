<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'code' => 'SVC-001',
                'name' => [
                    'en' => 'Engine Oil/Oil Filter',
                    'ar' => 'زيت المحرك / فلتر الزيت',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 1,
            ],
            [
                'code' => 'SVC-002',
                'name' => [
                    'en' => 'Gear Oil/Gear Oil filter',
                    'ar' => 'زيت الجير / فلتر الزيت',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 2,
            ],
            [
                'code' => 'SVC-003',
                'name' => [
                    'en' => 'Differential Oil Change',
                    'ar' => 'زيت الدفريشن',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 3,
            ],
            [
                'code' => 'SVC-004',
                'name' => [
                    'en' => 'Radiator Welding / Cleaning',
                    'ar' => 'تنظيف / تلحيم الراديتور',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 4,
            ],
            [
                'code' => 'SVC-005',
                'name' => [
                    'en' => 'New Radiator / Service',
                    'ar' => 'تركيب / خدمة الراديتور الجديد',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 5,
            ],
            [
                'code' => 'SVC-006',
                'name' => [
                    'en' => 'Radiator Cover Change',
                    'ar' => 'تغير غطاء الراديتور',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 6,
            ],
            [
                'code' => 'SVC-007',
                'name' => [
                    'en' => 'Condenser (New / Old) Service',
                    'ar' => 'خدمة الكوندنس (جديد / قديم)',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 7,
            ],
            [
                'code' => 'SVC-008',
                'name' => [
                    'en' => 'Battery',
                    'ar' => 'بطارية',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 8,
            ],
            [
                'code' => 'SVC-009',
                'name' => [
                    'en' => 'AC Compressor (New / Old Labor)',
                    'ar' => 'خدمة كومبريسور التكييف (جديد / قديم)',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 9,
            ],
            [
                'code' => 'SVC-010',
                'name' => [
                    'en' => 'Dashboard Service',
                    'ar' => 'خدمة فك طبلون السيارة',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 10,
            ],
            [
                'code' => 'SVC-011',
                'name' => [
                    'en' => 'AC Evaporator Service',
                    'ar' => 'خدمة ثلاجة التكييف',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 11,
            ],
            [
                'code' => 'SVC-012',
                'name' => [
                    'en' => 'AC Expansion Valve',
                    'ar' => 'خدمة فالف التكييف',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 12,
            ],
            [
                'code' => 'SVC-013',
                'name' => [
                    'en' => 'AC Gas/Compressor Oil',
                    'ar' => 'غاز التكييف / زيت الكومبريسور',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 13,
            ],
            [
                'code' => 'SVC-014',
                'name' => [
                    'en' => 'AC Hose Change / Welding',
                    'ar' => 'تغير هوز التكييف / تلحيم الهوز',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 14,
            ],
            [
                'code' => 'SVC-015',
                'name' => [
                    'en' => 'Power Steering Hose Change',
                    'ar' => 'تغير هوز الباور ستيرنق',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 15,
            ],
            [
                'code' => 'SVC-016',
                'name' => [
                    'en' => 'AC Cabin Filter Change',
                    'ar' => 'تغير فلتر التكييف',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 16,
            ],
            [
                'code' => 'SVC-017',
                'name' => [
                    'en' => 'Disc Polish Service',
                    'ar' => 'خدمة ثورنة الدرامويلات',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 17,
            ],
            [
                'code' => 'SVC-018',
                'name' => [
                    'en' => 'Compressor Service',
                    'ar' => 'خدمة الكومبريسور',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 18,
            ],
            [
                'code' => 'SVC-019',
                'name' => [
                    'en' => 'Alternator Service',
                    'ar' => 'خدمة داينمو السيارة',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 19,
            ],
            [
                'code' => 'SVC-020',
                'name' => [
                    'en' => 'Starter Motor Service',
                    'ar' => 'خدمة موتور التشغيل',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 20,
            ],
            [
                'code' => 'SVC-021',
                'name' => [
                    'en' => 'Fan Motor Service',
                    'ar' => 'خدمة مراوح السيارة',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 21,
            ],
            [
                'code' => 'SVC-022',
                'name' => [
                    'en' => 'Belt Change',
                    'ar' => 'تغير سير',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 22,
            ],
            [
                'code' => 'SVC-023',
                'name' => [
                    'en' => 'Bulb Change',
                    'ar' => 'تغير لمبة',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 23,
            ],
            [
                'code' => 'SVC-024',
                'name' => [
                    'en' => 'Lower / Upper Arm bush Service',
                    'ar' => 'خدمة بوشات الأذرعة',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 24,
            ],
            [
                'code' => 'SVC-025',
                'name' => [
                    'en' => 'Link rod / Tie end rod / Ball joint Service',
                    'ar' => 'خدمة رودات / بال جوينت',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 25,
            ],
            [
                'code' => 'SVC-026',
                'name' => [
                    'en' => 'Axle / CV joint Service',
                    'ar' => 'خدمة أكسل / سي في',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 26,
            ],
            [
                'code' => 'SVC-027',
                'name' => [
                    'en' => 'Fuel Pump / Tank Service',
                    'ar' => 'خدمة مضخة البترول / خزان البترول',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 27,
            ],
            [
                'code' => 'SVC-028',
                'name' => [
                    'en' => 'Engine Gasket',
                    'ar' => 'خدمة جازكيت المكينة',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 28,
            ],
            [
                'code' => 'SVC-029',
                'name' => [
                    'en' => 'Manifold Service',
                    'ar' => 'خدمة دبة البيئة',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 29,
            ],
            [
                'code' => 'SVC-030',
                'name' => [
                    'en' => 'Catalytic Service',
                    'ar' => 'خدمة دبة البيئة',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 30,
            ],
            [
                'code' => 'SVC-031',
                'name' => [
                    'en' => 'Exhaust Gasket Service',
                    'ar' => 'خدمة جازكيت الاكزوز',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 31,
            ],
            [
                'code' => 'SVC-032',
                'name' => [
                    'en' => 'Bearing Installation',
                    'ar' => 'تركيب بيرنق',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 32,
            ],
            [
                'code' => 'SVC-033',
                'name' => [
                    'en' => 'Plug / Throttle / Coil Service',
                    'ar' => 'خدمة بواجي / ثروتل / كويلات',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 33,
            ],
            [
                'code' => 'SVC-034',
                'name' => [
                    'en' => 'Wheel Welding',
                    'ar' => 'تلحيم رنقات',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 34,
            ],
            [
                'code' => 'SVC-035',
                'name' => [
                    'en' => 'Worker outside Labor',
                    'ar' => 'رسوم شغل خارجي',
                ],
                'unit' => 'hrs',
                'unit_price' => 0.00,
                'sort_order' => 35,
            ],
            [
                'code' => 'SVC-036',
                'name' => [
                    'en' => 'General Mechanical Labor',
                    'ar' => 'رسوم شغل ميكانيكي عام',
                ],
                'unit' => 'hrs',
                'unit_price' => 0.00,
                'sort_order' => 36,
            ],
            [
                'code' => 'SVC-037',
                'name' => [
                    'en' => 'General Electrical Labor',
                    'ar' => 'رسوم شغل كهربائي عام',
                ],
                'unit' => 'hrs',
                'unit_price' => 0.00,
                'sort_order' => 37,
            ],
            [
                'code' => 'SVC-038',
                'name' => [
                    'en' => 'Others',
                    'ar' => 'خدمة أخرى',
                ],
                'unit' => 'pcs',
                'unit_price' => 0.00,
                'sort_order' => 38,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['code' => $service['code']],
                [
                    'name' => $service['name'],
                    'unit' => $service['unit'],
                    'unit_price' => $service['unit_price'],
                    'is_active' => true,
                    'sort_order' => $service['sort_order'],
                ]
            );
        }

        $this->command->info('Seeded ' . count($services) . ' services successfully.');
    }
}
