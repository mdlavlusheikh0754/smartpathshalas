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
        Schema::table('teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('teachers', 'nid_file')) {
                $table->string('nid_file')->nullable()->after('photo');
            }
            if (!Schema::hasColumn('teachers', 'educational_certificate')) {
                $table->string('educational_certificate')->nullable()->after('nid_file');
            }
            if (!Schema::hasColumn('teachers', 'experience_certificate')) {
                $table->string('experience_certificate')->nullable()->after('educational_certificate');
            }
            if (!Schema::hasColumn('teachers', 'medical_certificate')) {
                $table->string('medical_certificate')->nullable()->after('experience_certificate');
            }
            if (!Schema::hasColumn('teachers', 'other_documents')) {
                $table->string('other_documents')->nullable()->after('medical_certificate');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'nid_file')) {
                $table->dropColumn('nid_file');
            }
            if (Schema::hasColumn('teachers', 'educational_certificate')) {
                $table->dropColumn('educational_certificate');
            }
            if (Schema::hasColumn('teachers', 'experience_certificate')) {
                $table->dropColumn('experience_certificate');
            }
            if (Schema::hasColumn('teachers', 'medical_certificate')) {
                $table->dropColumn('medical_certificate');
            }
            if (Schema::hasColumn('teachers', 'other_documents')) {
                $table->dropColumn('other_documents');
            }
        });
    }
};
