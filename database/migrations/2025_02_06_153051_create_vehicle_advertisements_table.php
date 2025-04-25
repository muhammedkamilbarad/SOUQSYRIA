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
            $table->integer('year');
            $table->foreignId('brand_id')->constrained('vehicle_brands')->onDelete('restrict');
            $table->foreignId('model_id')->constrained('vehicle_models')->onDelete('restrict');
            $table->Enum('fuel_type', array_column(FuelType::cases(), 'name'));
            $table->integer('horsepower')->unsigned()->nullable();
            $table->Enum('condition', ['NEW', 'USED'])->default('USED');
            $table->timestamps();

            $table->index('color');
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
