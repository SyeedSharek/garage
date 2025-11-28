<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('payment_gateway', [
                PaymentGateway::CARD->value,
                PaymentGateway::CASH->value,
            ])->default(PaymentGateway::CASH->value);
            $table->enum('status', [
                PaymentStatus::DRAFT->value,
                PaymentStatus::PENDING->value,
                PaymentStatus::DUE->value,
                PaymentStatus::PAID->value,
                PaymentStatus::PARTIAL->value,
                PaymentStatus::OVERDUE->value,
                PaymentStatus::CANCELLED->value,
            ])->default(PaymentStatus::DRAFT->value);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->dateTime('invoice_date');
            $table->date('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['invoice_number']);
            $table->index(['order_id']);
            $table->index(['customer_id', 'invoice_date']);
            $table->index(['status', 'invoice_date']);
            $table->index(['payment_gateway', 'status']);
            $table->index(['due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
