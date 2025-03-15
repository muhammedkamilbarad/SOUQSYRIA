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
        Schema::create('house_advertisements', function (Blueprint $table) {
            $table->foreignId('advertisement_id')->primary()->constrained('advertisements')->onDelete('cascade');
            $table->integer('number_of_rooms')->unsigned();
            $table->integer('number_of_bathrooms')->unsigned();
            $table->integer('building_age')->unsigned();
            $table->decimal('square_meters', 10, 2);
            $table->integer('floor')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('house_advertisements');
    }
};
