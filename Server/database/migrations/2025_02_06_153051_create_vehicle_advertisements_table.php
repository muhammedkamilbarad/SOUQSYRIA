<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\FuelType;
use App\Enums\TransmissionType;
use App\Enums\Colors;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicle_advertisements', function (Blueprint $table) {
            $table->foreignId('advertisement_id')->primary()->constrained('advertisements')->onDelete('cascade');
            $table->Enum('color', array_column(Colors::cases(), 'name'));
            $table->integer('mileage')->unsigned();
            $table->integer('year');
            $table->foreignId('brand_id')->constrained('vehicle_brands')->onDelete('restrict');
            $table->foreignId('model_id')->constrained('vehicle_models')->onDelete('restrict');
            $table->Enum('transmission_type', array_column(TransmissionType::cases(), 'name'));
            $table->Enum('fuel_type', array_column(FuelType::cases(), 'name'));
            $table->integer('horsepower')->unsigned();
            $table->tinyInteger('cylinders')->unsigned()->nullable();
            $table->decimal('engine_capacity', 8, 2)->nullable();
            $table->Enum('condition', ['NEW', 'USED'])->default('USED');
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
