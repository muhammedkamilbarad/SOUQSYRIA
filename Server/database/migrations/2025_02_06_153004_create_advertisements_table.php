<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\SyriaCities;
use App\Enums\CategoryType;
use Illuminate\Support\Str;

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
            $table->string('slug');
            $table->text('description');
            $table->decimal('price', 12, 2);
            $table->Enum('currency',['SYP ','TRY','USD'])->default('USD');
            $table->Enum('city', array_column(SyriaCities::cases(), 'name'));
            $table->string('location', 255);
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->Enum('ads_status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->Enum('active_status', ['active', 'inactive'])->default('inactive');
            $table->Enum('type', ['rent', 'sale']);
            $table->Enum('image_upload_status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->timestamps();

            $table->index('ads_status');
            $table->index('active_status');
            $table->index(['type']);
            $table->index(['city','category_id']);
            $table->index(['user_id']);
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
