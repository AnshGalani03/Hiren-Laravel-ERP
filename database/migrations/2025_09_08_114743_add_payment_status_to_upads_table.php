<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('upads', function (Blueprint $table) {
            if (!Schema::hasColumn('upads', 'salary_paid')) {
                $table->boolean('salary_paid')->default(false)->after('salary');
            }
            if (!Schema::hasColumn('upads', 'upad_paid')) {
                $table->boolean('upad_paid')->default(false)->after('upad');
            }
        });
    }

    public function down()
    {
        Schema::table('upads', function (Blueprint $table) {
            if (Schema::hasColumn('upads', 'salary_paid')) {
                $table->dropColumn('salary_paid');
            }
            if (Schema::hasColumn('upads', 'upad_paid')) {
                $table->dropColumn('upad_paid');
            }
        });
    }
};
