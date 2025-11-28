<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            // Individual Customers
            [
                'name' => 'Ahmed Al-Mansoori',
                'phone' => '+974 3312 4567',
                'email' => 'ahmed.almansoori@example.com',
                'address' => 'Al Rayyan, Doha',
                'city' => 'Doha',
                'country' => 'Qatar',
                'type' => 'individual',
                'is_active' => true,
            ],
            [
                'name' => 'Mohammed Al-Thani',
                'phone' => '+974 3313 5678',
                'email' => 'mohammed.althani@example.com',
                'address' => 'Al Wakrah, Doha',
                'city' => 'Doha',
                'country' => 'Qatar',
                'type' => 'individual',
                'is_active' => true,
            ],
            [
                'name' => 'Fatima Al-Kuwari',
                'phone' => '+974 3314 6789',
                'email' => 'fatima.alkuwari@example.com',
                'address' => 'Al Khor, Doha',
                'city' => 'Doha',
                'country' => 'Qatar',
                'type' => 'individual',
                'is_active' => true,
            ],
            [
                'name' => 'Khalid Al-Suwaidi',
                'phone' => '+974 3315 7890',
                'email' => 'khalid.alsuwaidi@example.com',
                'address' => 'West Bay, Doha',
                'city' => 'Doha',
                'country' => 'Qatar',
                'type' => 'individual',
                'is_active' => true,
            ],
            [
                'name' => 'Sara Al-Attiyah',
                'phone' => '+974 3316 8901',
                'email' => 'sara.alattiyah@example.com',
                'address' => 'Al Sadd, Doha',
                'city' => 'Doha',
                'country' => 'Qatar',
                'type' => 'individual',
                'is_active' => true,
            ],
            [
                'name' => 'Omar Al-Hamad',
                'phone' => '+974 3317 9012',
                'email' => null,
                'address' => 'Al Gharrafa, Doha',
                'city' => 'Doha',
                'country' => 'Qatar',
                'type' => 'individual',
                'is_active' => true,
            ],
            [
                'name' => 'Layla Al-Mahmoud',
                'phone' => '+974 3318 0123',
                'email' => 'layla.almahmoud@example.com',
                'address' => 'Al Waab, Doha',
                'city' => 'Doha',
                'country' => 'Qatar',
                'type' => 'individual',
                'is_active' => true,
            ],
            [
                'name' => 'Youssef Al-Muraikhi',
                'phone' => '+974 3319 1234',
                'email' => null,
                'address' => 'Al Aziziyah, Doha',
                'city' => 'Doha',
                'country' => 'Qatar',
                'type' => 'individual',
                'is_active' => false,
            ],
            // Company Customers
            [
                'name' => 'Qatar Auto Services LLC',
                'phone' => '+974 4401 2345',
                'email' => 'info@qatarauto.qa',
                'address' => 'Industrial Area, Doha',
                'city' => 'Doha',
                'country' => 'Qatar',
                'cr_number' => 'CR-2023-001234',
                'tax_number' => 'TAX-QA-123456',
                'type' => 'company',
                'company_name' => 'Qatar Auto Services LLC',
                'is_active' => true,
            ],
            [
                'name' => 'Gulf Motors Trading W.L.L.',
                'phone' => '+974 4402 3456',
                'email' => 'contact@gulfmotors.qa',
                'address' => 'Al Sadd, Doha',
                'city' => 'Doha',
                'country' => 'Qatar',
                'cr_number' => 'CR-2022-002345',
                'tax_number' => 'TAX-QA-234567',
                'type' => 'company',
                'company_name' => 'Gulf Motors Trading W.L.L.',
                'is_active' => true,
            ],
            [
                'name' => 'Doha Car Care Center',
                'phone' => '+974 4403 4567',
                'email' => 'info@dohacarcare.qa',
                'address' => 'Al Rayyan, Doha',
                'city' => 'Doha',
                'country' => 'Qatar',
                'cr_number' => 'CR-2023-003456',
                'tax_number' => 'TAX-QA-345678',
                'type' => 'company',
                'company_name' => 'Doha Car Care Center',
                'is_active' => true,
            ],
            [
                'name' => 'Premium Auto Solutions',
                'phone' => '+974 4404 5678',
                'email' => 'sales@premiumauto.qa',
                'address' => 'West Bay, Doha',
                'city' => 'Doha',
                'country' => 'Qatar',
                'cr_number' => 'CR-2021-004567',
                'tax_number' => 'TAX-QA-456789',
                'type' => 'company',
                'company_name' => 'Premium Auto Solutions',
                'is_active' => true,
            ],
            [
                'name' => 'Al Khaleej Vehicle Services',
                'phone' => '+974 4405 6789',
                'email' => 'info@alkhaleejvehicles.qa',
                'address' => 'Al Wakrah, Doha',
                'city' => 'Doha',
                'country' => 'Qatar',
                'cr_number' => 'CR-2020-005678',
                'tax_number' => 'TAX-QA-567890',
                'type' => 'company',
                'company_name' => 'Al Khaleej Vehicle Services',
                'is_active' => true,
            ],
            [
                'name' => 'Express Garage Services',
                'phone' => '+974 4406 7890',
                'email' => 'contact@expressgarage.qa',
                'address' => 'Al Gharrafa, Doha',
                'city' => 'Doha',
                'country' => 'Qatar',
                'cr_number' => 'CR-2023-006789',
                'tax_number' => null,
                'type' => 'company',
                'company_name' => 'Express Garage Services',
                'is_active' => false,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(
                [
                    'phone' => $customer['phone'],
                ],
                $customer
            );
        }

        $this->command->info('Seeded ' . count($customers) . ' customers successfully.');
    }
}

