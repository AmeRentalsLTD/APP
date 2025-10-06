<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount_net', 10, 2);
            $table->unsignedTinyInteger('vat_rate')->default(0);
            $table->date('held_at');
            $table->date('released_at')->nullable();
            $table->enum('status', ['held', 'partially_released', 'released', 'withheld'])->default('held');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
