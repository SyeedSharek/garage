<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\OrderStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', [
                OrderStatus::DRAFT->value,
                OrderStatus::PENDING->value,
                OrderStatus::CONFIRMED->value,
                OrderStatus::CANCELLED->value,
            ])->default(OrderStatus::DRAFT->value);
            $table->dateTime('order_date');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['order_number']);
            $table->index(['customer_id', 'order_date']);
            $table->index(['status', 'order_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
