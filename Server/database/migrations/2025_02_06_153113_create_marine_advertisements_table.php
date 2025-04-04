<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\MarineType;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('marine_advertisements', function (Blueprint $table) {
            $table->foreignId('advertisement_id')->primary()->constrained('advertisements')->onDelete('cascade');
            $table->Enum('marine_type', array_column(MarineType::cases(), 'name'));
            $table->decimal('length', 8, 2)->nullable();
            $table->integer('max_capacity')->unsigned()->nullable();
            $table->timestamps();

            $table->index('marine_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marine_advertisements');
    }
};
