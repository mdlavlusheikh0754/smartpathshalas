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
        Schema::table('academic_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('academic_sessions', 'description')) {
                $table->text('description')->nullable()->after('is_active');
            }
            if (!Schema::hasColumn('academic_sessions', 'total_students')) {
                $table->integer('total_students')->default(0)->after('description');
            }
            if (!Schema::hasColumn('academic_sessions', 'total_teachers')) {
                $table->integer('total_teachers')->default(0)->after('total_students');
            }
            if (!Schema::hasColumn('academic_sessions', 'total_staff')) {
                $table->integer('total_staff')->default(0)->after('total_teachers');
            }
            if (!Schema::hasColumn('academic_sessions', 'total_classrooms')) {
                $table->integer('total_classrooms')->default(0)->after('total_staff');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_sessions', function (Blueprint $table) {
            $table->dropColumn(['description', 'total_students', 'total_teachers', 'total_staff', 'total_classrooms']);
        });
    }
};
