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
		Schema::create('rental_agreements', function (Blueprint $table) {
			$table->id();
			$table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
			$table->foreignId('customer_id')->constrained()->cascadeOnDelete();

			$table->date('start_date');
			$table->date('end_date')->nullable();

			// Termeni comerciali
			$table->string('billing_cycle')->default('weekly'); // weekly|monthly
			$table->decimal('rate_amount',10,2);                // ex: 350.00 / week
			$table->decimal('deposit_amount',10,2)->default(500);
			$table->unsignedSmallInteger('notice_days')->default(14);
			$table->unsignedSmallInteger('deposit_release_days')->default(14);

			// Politici
			$table->string('insurance_option')->default('company'); // company|own
			$table->string('mileage_policy')->default('unlimited'); // unlimited|cap
			$table->unsignedInteger('mileage_cap')->nullable();
			$table->decimal('cleaning_fee',10,2)->default(50);
			$table->decimal('admin_fee',10,2)->default(25);
			$table->boolean('no_smoking')->default(true);
			$table->boolean('tracking_enabled')->default(true);
			$table->string('payment_day')->default('friday'); // pentru recurență
			$table->string('status')->default('active'); // draft|active|paused|ended

			$table->timestamps();

			// Un singur contract activ per vehicul
			$table->unique(['vehicle_id','status'], 'vehicle_active_unique')
				  ->where('status', 'active');
		});
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_agreements');
    }
};
