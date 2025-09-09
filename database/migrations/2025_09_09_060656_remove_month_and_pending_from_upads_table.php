<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('upads', function (Blueprint $table) {
            if (Schema::hasColumn('upads', 'month')) {
                $table->dropColumn('month');
            }
            if (Schema::hasColumn('upads', 'pending')) {
                $table->dropColumn('pending');
            }
        });
    }

    public function down()
    {
        Schema::table('upads', function (Blueprint $table) {
            $table->string('month')->nullable();
            $table->decimal('pending', 10, 2)->default(0);
        });
    }
};
