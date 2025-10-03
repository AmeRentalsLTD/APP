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
        Schema::create('vehicle_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20);
            $table->date('inspected_at');
            $table->string('front_image_path');
            $table->string('rear_image_path');
            $table->string('left_image_path');
            $table->string('right_image_path');
            $table->string('tyres_image_path');
            $table->string('windscreen_image_path');
            $table->string('mirrors_image_path');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['vehicle_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_inspections');
    }
};
