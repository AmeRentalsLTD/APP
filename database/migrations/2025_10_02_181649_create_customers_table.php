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
		Schema::create('customers', function (Blueprint $table) {
			$table->id();
			$table->string('type')->default('individual'); // individual|company
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('company_name')->nullable();
			$table->string('email')->nullable();
			$table->string('phone')->nullable();
			$table->string('address_line1')->nullable();
			$table->string('address_line2')->nullable();
			$table->string('city')->nullable();
			$table->string('postcode')->nullable(); // NN4 0RT
			$table->string('country')->default('United Kingdom');

			// opționale pt șofer
			$table->string('driving_license_no')->nullable();
			$table->date('dob')->nullable();
			$table->string('nin')->nullable();

			$table->timestamps();
		});
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
