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
        Schema::table('fee_collections', function (Blueprint $table) {
            if (!Schema::hasColumn('fee_collections', 'fee_type_name')) {
                $table->string('fee_type_name')->nullable()->after('fee_type');
            }
            if (!Schema::hasColumn('fee_collections', 'month')) {
                $table->string('month')->nullable()->after('fee_type_name');
            }
            if (!Schema::hasColumn('fee_collections', 'month_count')) {
                $table->integer('month_count')->default(1)->after('month');
            }
            if (!Schema::hasColumn('fee_collections', 'year')) {
                $table->string('year')->nullable()->after('academic_year');
            }
            if (!Schema::hasColumn('fee_collections', 'voucher_no')) {
                $table->string('voucher_no')->nullable()->after('receipt_number');
            }
            if (!Schema::hasColumn('fee_collections', 'zakat_amount')) {
                $table->decimal('zakat_amount', 10, 2)->default(0)->after('discount_amount');
            }
            if (!Schema::hasColumn('fee_collections', 'grant_amount')) {
                $table->decimal('grant_amount', 10, 2)->default(0)->after('zakat_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fee_collections', function (Blueprint $table) {
            $table->dropColumn(['fee_type_name', 'month', 'month_count', 'year', 'voucher_no', 'zakat_amount', 'grant_amount']);
        });
    }
};
