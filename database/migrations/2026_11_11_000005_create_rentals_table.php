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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('library_book_id')->constrained('library_books')->restrictOnDelete();
            $table->enum('fulfillment_method', ['delivery', 'pickup']);
            $table->string('recipient_name');
            $table->string('recipient_phone', 20);
            $table->text('recipient_address')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedSmallInteger('days');
            $table->unsignedBigInteger('daily_price');
            $table->unsignedBigInteger('deposit');
            $table->unsignedBigInteger('rental_fee');
            $table->unsignedBigInteger('total_amount');
            $table->enum('status', [
                'pending_verification',
                'approved',
                'on_rent',
                'returned',
                'rejected',
                'cancelled',
            ])->default('pending_verification')->index();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('terms_accepted_at');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('handed_over_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->unsignedBigInteger('deposit_deduction')->default(0);
            $table->enum('deposit_refund_status', ['none', 'pending', 'refunded', 'forfeited'])
                ->default('none');
            $table->timestamp('deposit_refunded_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
