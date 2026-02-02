<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('student_attendances', function (Blueprint $table) {
            $table->integer('device_user_id')->nullable()->after('marked_by');
            $table->tinyInteger('verify_type')->nullable()->after('device_user_id')->comment('1=fingerprint, 15=password, etc');
            $table->tinyInteger('in_out_mode')->nullable()->after('verify_type')->comment('0=check in, 1=check out');
            $table->timestamp('device_timestamp')->nullable()->after('in_out_mode');
            
            $table->index('device_user_id');
            $table->index('verify_type');
        });
    }

    public function down()
    {
        Schema::table('student_attendances', function (Blueprint $table) {
            $table->dropIndex(['device_user_id']);
            $table->dropIndex(['verify_type']);
            $table->dropColumn(['device_user_id', 'verify_type', 'in_out_mode', 'device_timestamp']);
        });
    }
};