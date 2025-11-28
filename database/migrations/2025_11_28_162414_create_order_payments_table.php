<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\OrderPaymentType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->comment('Recorded by user');
            $table->enum('payment_type', [
                OrderPaymentType::SUBTOTAL->value,
                OrderPaymentType::TAX->value,
                OrderPaymentType::DISCOUNT->value,
                OrderPaymentType::TOTAL->value,
            ]);
            $table->decimal('amount', 10, 2);
            $table->boolean('is_paid')->default(false);
            $table->boolean('is_payable')->default(true);
            $table->dateTime('payment_date');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['order_id', 'payment_date']);
            $table->index(['order_id', 'payment_type']);
            $table->index(['invoice_id', 'payment_date']);
            $table->index(['payment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payments');
    }
};
