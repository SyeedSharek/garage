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
        Schema::create('garage_details', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->string('logo')->nullable();
            $table->string('slogan')->nullable();
            $table->string('slogan_ar')->nullable();
            $table->string('mobile')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('cr_number')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('po_box')->nullable();
            $table->string('street_number')->nullable();
            $table->string('street_number_ar')->nullable();
            $table->string('zone')->nullable();
            $table->string('zone_ar')->nullable();
            $table->string('building_number')->nullable();
            $table->string('building_number_ar')->nullable();
            $table->string('wakalat_street')->nullable();
            $table->string('wakalat_street_ar')->nullable();
            $table->string('shop_number')->nullable();
            $table->string('shop_number_ar')->nullable();
            $table->string('area')->nullable();
            $table->string('area_ar')->nullable();
            $table->string('city')->nullable();
            $table->string('city_ar')->nullable();
            $table->string('country')->default('Qatar');
            $table->string('country_ar')->default('قطر');
            $table->text('address')->nullable();
            $table->text('address_ar')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Insert default garage details
        DB::table('garage_details')->insert([
            'name' => 'AUTOTAB SPARE PARTS INTERNATIONAL TRADING W.L.L',
            'name_ar' => 'اتوتاب سبير بارتس انتر ناشيونال تريدينغ .م.م',
            'slogan' => 'SUSPENSION PARTS FOR ALL CARS',
            'slogan_ar' => 'قطع غيار لجميع السيارات',
            'mobile' => '+974 3005 5831',
            'phone' => '4142 3974',
            'email' => 'infoautotabpartsqatar@gmail.com',
            'cr_number' => '182700',
            'po_box' => '37208',
            'street_number' => '105',
            'street_number_ar' => '١٠٥',
            'zone' => '57',
            'zone_ar' => '٥٧',
            'building_number' => '183',
            'building_number_ar' => '١٨٣',
            'wakalat_street' => '28',
            'wakalat_street_ar' => '٢٨',
            'shop_number' => '10/11',
            'shop_number_ar' => '١٠/١١',
            'area' => 'Industrial Area',
            'area_ar' => 'منطقة صناعية',
            'city' => 'Doha',
            'city_ar' => 'الدوحة',
            'country' => 'Qatar',
            'country_ar' => 'قطر',
            'address' => 'Street No: 105, Zone: 57, Build No: 183, Wakalat Street: 28, Shop No: 10/11, Industrial Area, Doha, Qatar',
            'address_ar' => 'شارع رقم ١٠٥ المنطقة: ٥٧، رقم المبنى: ١٨٣، شارع الوكالات: ٢٨، محل رقم: ١٠/١١، منطقة صناعية، الدوحة، قطر',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garage_details');
    }
};
