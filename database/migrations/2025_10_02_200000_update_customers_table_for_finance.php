<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (! Schema::hasColumn('customers', 'name')) {
                $table->string('name')->nullable()->after('id');
            }

            if (! Schema::hasColumn('customers', 'billing_address')) {
                $table->text('billing_address')->nullable()->after('phone');
            }

            if (! Schema::hasColumn('customers', 'vat_number')) {
                $table->string('vat_number')->nullable()->after('billing_address');
            }

            if (! Schema::hasColumn('customers', 'company_number')) {
                $table->string('company_number')->nullable()->after('vat_number');
            }

            if (! Schema::hasColumn('customers', 'notes')) {
                $table->text('notes')->nullable()->after('company_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'notes')) {
                $table->dropColumn('notes');
            }

            if (Schema::hasColumn('customers', 'company_number')) {
                $table->dropColumn('company_number');
            }

            if (Schema::hasColumn('customers', 'vat_number')) {
                $table->dropColumn('vat_number');
            }

            if (Schema::hasColumn('customers', 'billing_address')) {
                $table->dropColumn('billing_address');
            }

            if (Schema::hasColumn('customers', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
