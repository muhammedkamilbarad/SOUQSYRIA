<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TransmissionType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('land_vehicle_attributes', function (Blueprint $table) {
            $table->foreignId('advertisement_id')->primary()->constrained('advertisements')->onDelete('cascade');
            $table->integer('mileage')->unsigned();
            $table->Enum('transmission_type', array_column(TransmissionType::cases(), 'name'));
            $table->tinyInteger('cylinders')->unsigned()->nullable();
            $table->decimal('engine_capacity', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('land_vehicle_attributes');
    }
};
