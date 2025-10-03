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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            // Basic
            $table->string('registration', 15)->unique(); // VRM (ex: EU14YMF)
            $table->string('make', 60)->nullable();
            $table->string('model', 60)->nullable();
            $table->string('variant', 60)->nullable();
            $table->unsignedSmallInteger('year')->nullable(); // 1900..(Y+1)
            $table->unsignedInteger('mileage')->default(0);

            // Compliance
            $table->date('mot_expiry')->nullable();
            $table->date('road_tax_due')->nullable();

            // Financials
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->decimal('monthly_finance', 10, 2)->nullable();
            $table->boolean('has_vat')->default(true);

            // Status
            $table->string('status', 20)->default('available')->index();

            // Notes
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
