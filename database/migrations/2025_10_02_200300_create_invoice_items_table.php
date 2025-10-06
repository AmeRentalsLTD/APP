<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['rent', 'fuel', 'damage', 'fee', 'other']);
            $table->string('description');
            $table->decimal('qty', 10, 2)->default(1);
            $table->decimal('unit_price_net', 10, 2);
            $table->unsignedTinyInteger('vat_rate')->default(20);
            $table->decimal('line_total_net', 10, 2);
            $table->decimal('line_tax', 10, 2);
            $table->decimal('line_total_gross', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
