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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->morphs('noteable'); // Creates noteable_id and noteable_type
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->comment('User who created the note');
            $table->text('content');
            $table->boolean('is_important')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['noteable_id', 'noteable_type']);
            $table->index(['user_id']);
            $table->index(['is_important']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
