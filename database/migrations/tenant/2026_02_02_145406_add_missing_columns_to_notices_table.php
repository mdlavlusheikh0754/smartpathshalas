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
        Schema::table('notices', function (Blueprint $table) {
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('notices', 'status')) {
                $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            }
            
            // Add priority column if it doesn't exist
            if (!Schema::hasColumn('notices', 'priority')) {
                $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            }
            
            // Add attachment column if it doesn't exist
            if (!Schema::hasColumn('notices', 'attachment')) {
                $table->string('attachment')->nullable();
            }
            
            // Add expire_date column if it doesn't exist
            if (!Schema::hasColumn('notices', 'expire_date')) {
                $table->date('expire_date')->nullable();
            }
            
            // Add author_id column if it doesn't exist
            if (!Schema::hasColumn('notices', 'author_id')) {
                $table->unsignedBigInteger('author_id')->nullable();
                // Add foreign key constraint if users table exists
                if (Schema::hasTable('users')) {
                    $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $columns = ['status', 'priority', 'attachment', 'expire_date', 'author_id'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('notices', $column)) {
                    if ($column === 'author_id') {
                        $table->dropForeign(['author_id']);
                    }
                    $table->dropColumn($column);
                }
            }
        });
    }
};