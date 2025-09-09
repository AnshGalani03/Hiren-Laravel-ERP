<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('upads', function (Blueprint $table) {
            $table->boolean('salary_paid')->default(false)->after('salary');
            $table->boolean('upad_paid')->default(false)->after('upad');
        });
    }

    public function down()
    {
        Schema::table('upads', function (Blueprint $table) {
            $table->dropColumn(['salary_paid', 'upad_paid']);
        });
    }
};
