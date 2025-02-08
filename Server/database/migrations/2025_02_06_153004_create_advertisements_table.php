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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description');
            $table->decimal('price', 12, 2);
            $table->foreignId('city_id')->constrained('cities')->onDelete('restrict');
            $table->string('location', 255);
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->Enum('ads_status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->Enum('active_status', ['active', 'inactive'])->default('active');
            $table->enum('type', ['rent', 'sale']);
            $table->timestamps();

            $table->index('ads_status');
            $table->index('active_status');
            $table->index(['type']);
            $table->index(['city_id','category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
