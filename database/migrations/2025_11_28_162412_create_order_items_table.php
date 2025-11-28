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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('restrict')->comment('Service reference');
            $table->json('description')->comment('Translatable: {"en": "Description", "ar": "الوصف"}');

            $table->integer('quantity')->default(1);
            $table->string('unit')->default('pcs')->comment('Unit keyword: pcs, hrs, etc.');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('amount', 10, 2)->comment('quantity * unit_price');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['order_id']);
            $table->index(['order_id', 'sort_order']);
            $table->index(['service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
