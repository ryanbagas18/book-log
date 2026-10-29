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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('genre')->nullable();
            $table->string('language')->default('Indonesian');
            $table->unsignedSmallInteger('pages')->nullable();
            $table->enum('status', ['to_read', 'reading', 'finished', 'dropped'])->default('to_read');
            $table->unsignedTinyInteger('rating')->nullable();
            $table->date('started_at')->nullable();
            $table->date('finished_at')->nullable();
            $table->enum('ownership', ['owned', 'skimmed', 'borrowed', 'wishlist'])->default('owned');
            $table->string('purchase_place')->nullable();
            $table->enum('condition', ['new', 'preloved'])->nullable();
            $table->date('purchase_date')->nullable();
            $table->unsignedBigInteger('price')->default(0);
            $table->string('cover_image')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'ownership']);
            $table->index('purchase_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
