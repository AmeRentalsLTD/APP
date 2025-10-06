<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('category', ['fuel', 'insurance', 'service', 'road_tax', 'mot', 'repairs', 'other']);
            $table->string('vendor')->nullable();
            $table->date('date');
            $table->decimal('net', 10, 2);
            $table->unsignedTinyInteger('vat_rate')->default(20);
            $table->decimal('tax', 10, 2);
            $table->decimal('gross', 10, 2);
            $table->string('reference')->nullable();
            $table->string('attachment_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
