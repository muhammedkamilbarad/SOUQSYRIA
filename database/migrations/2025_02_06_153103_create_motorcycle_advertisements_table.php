<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Colors;
use App\Enums\CoolingType;
use App\Enums\MotorcycleType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('motorcycle_advertisements', function (Blueprint $table) {
            $table->foreignId('advertisement_id')->primary()->constrained('advertisements')->onDelete('cascade');
            $table->Enum('cooling_type', array_column(CoolingType::cases(), 'name'));
            $table->Enum('motorcycle_type', array_column(MotorcycleType::cases(), 'name'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motorcycle_advertisements');
    }
};
