<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->decimal('price_net', 10, 2);
            $table->unsignedTinyInteger('vat_rate')->default(20);
            $table->enum('frequency', ['weekly', 'monthly']);
            $table->decimal('deposit_net', 10, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->unsignedSmallInteger('notice_days')->default(14);
            $table->enum('status', ['active', 'paused', 'ended'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
