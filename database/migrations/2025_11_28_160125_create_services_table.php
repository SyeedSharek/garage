<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable()->comment('Service code for quick reference');
            $table->json('name')->comment('Translatable: {"en": "Service Name", "ar": "اسم الخدمة"}');
            $table->json('description')->nullable()->comment('Translatable description');
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->string('unit')->default('pcs')->comment('Unit keyword: pcs, hrs, etc. (translated via lang files)');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
