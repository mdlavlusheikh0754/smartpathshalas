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
        Schema::table('guardians', function (Blueprint $table) {
            if (!Schema::hasColumn('guardians', 'status')) {
                $table->string('status')->default('active')->after('password');
            }
            if (!Schema::hasColumn('guardians', 'photo')) {
                $table->string('photo')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guardians', function (Blueprint $table) {
            if (Schema::hasColumn('guardians', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('guardians', 'photo')) {
                $table->dropColumn('photo');
            }
        });
    }
};