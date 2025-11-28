<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('garage_branches', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('mobile')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('cr_number')->nullable();
            $table->string('unit')->nullable();
            $table->string('unit_ar')->nullable();
            $table->string('zone')->nullable();
            $table->string('zone_ar')->nullable();
            $table->string('street')->nullable();
            $table->string('street_ar')->nullable();
            $table->string('landmark')->nullable();
            $table->string('landmark_ar')->nullable();
            $table->string('road')->nullable();
            $table->string('road_ar')->nullable();
            $table->string('city')->nullable();
            $table->string('city_ar')->nullable();
            $table->string('country')->default('Qatar');
            $table->string('country_ar')->default('قطر');
            $table->text('address')->nullable();
            $table->text('address_ar')->nullable();
            $table->boolean('is_main')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_main']);
            $table->index(['is_active']);
        });

        // Insert default branch
        DB::table('garage_branches')->insert([
            'name' => 'AL AZIZIYA, SALWA ROAD',
            'name_ar' => 'الفرع: العزيزية طريق سلوى',
            'mobile' => '+974 77750 834',
            'phone' => '4145 8911',
            'email' => 'infoautotabpartsqatar@gmail.com',
            'cr_number' => '182700/01',
            'unit' => '18',
            'unit_ar' => '١٨',
            'zone' => '55',
            'zone_ar' => '٥٥',
            'street' => '185',
            'street_ar' => '١٨٥',
            'landmark' => 'NEAR QATARPOST AZIZIYA',
            'landmark_ar' => 'بالقرب من بريد قطر العزيزية',
            'road' => 'SALWA ROAD',
            'road_ar' => 'طريق سلوى',
            'city' => 'Doha',
            'city_ar' => 'الدوحة',
            'country' => 'Qatar',
            'country_ar' => 'قطر',
            'address' => 'NEAR QATARPOST AZIZIYA, SALWA ROAD, DOHA - QATAR',
            'address_ar' => 'بالقرب من بريد قطر العزيزية، طريق سلوى، الدوحة - قطر',
            'is_main' => false,
            'is_active' => true,
            'sort_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garage_branches');
    }
};
