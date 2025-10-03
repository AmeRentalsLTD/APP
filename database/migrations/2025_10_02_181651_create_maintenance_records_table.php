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
        Schema::create('maintenance_records', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('type', 40);
            $table->string('status', 30)->default('scheduled')->index();
            $table->unsignedInteger('odometer')->nullable();
            $table->date('scheduled_at')->nullable()->index();
            $table->date('completed_at')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('vendor', 120)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_records');
    }
};
