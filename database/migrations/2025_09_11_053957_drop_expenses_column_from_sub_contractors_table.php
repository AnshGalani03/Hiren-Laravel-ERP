<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sub_contractors', function (Blueprint $table) {
            $table->dropColumn('expenses');
        });
    }

    public function down()
    {
        Schema::table('sub_contractors', function (Blueprint $table) {
            $table->decimal('expenses', 15, 2)->default(0);
        });
    }
};
