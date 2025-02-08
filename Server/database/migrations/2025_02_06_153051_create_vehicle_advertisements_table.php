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
        Schema::create('vehicle_advertisements', function (Blueprint $table) {
            $table->foreignId('advertisement_id')->primary()->constrained('advertisements')->onDelete('cascade');
            $table->foreignId('color_id')->constrained('colors')->onDelete('restrict');
            $table->integer('mileage')->unsigned();
            $table->integer('year');
            $table->decimal('engine_capacity', 8, 2);
            $table->foreignId('brand_id')->constrained('vehicle_brands')->onDelete('restrict');
            $table->foreignId('model_id')->constrained('vehicle_models')->onDelete('restrict');
            $table->foreignId('fuel_type_id')->constrained('fuel_types')->onDelete('restrict');
            $table->integer('horsepower')->unsigned();
            $table->foreignId('transmission_id')->constrained('transmission_types')->onDelete('restrict');
            $table->enum('condition', ['NEW', 'USED'])->default('USED');
            $table->timestamps();

            $table->index('condition');
            $table->index(['brand_id', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_advertisements');
    }
};
