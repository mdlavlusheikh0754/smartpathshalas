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
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'monthly_fee_status')) {
                $table->string('monthly_fee_status')->default('pending')->after('status');
            }
            if (!Schema::hasColumn('students', 'last_monthly_payment')) {
                $table->timestamp('last_monthly_payment')->nullable()->after('monthly_fee_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['monthly_fee_status', 'last_monthly_payment']);
        });
    }
};
