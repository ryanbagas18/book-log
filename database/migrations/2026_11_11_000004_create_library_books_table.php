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
        Schema::create('library_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('genre_id')->constrained('genres')->restrictOnDelete();
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('isbn', 20)->nullable();
            $table->text('synopsis')->nullable();
            $table->string('goodreads_url')->nullable();
            $table->string('language')->default('Indonesian');
            $table->unsignedSmallInteger('pages')->nullable();
            $table->string('cover_image')->nullable();
            $table->unsignedBigInteger('deposit_price')->default(0);
            $table->unsignedBigInteger('daily_price');
            $table->unsignedSmallInteger('stock')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('genre_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_books');
    }
};
