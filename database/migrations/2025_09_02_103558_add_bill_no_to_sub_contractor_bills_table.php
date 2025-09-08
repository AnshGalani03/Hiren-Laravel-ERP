<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sub_contractor_bills', function (Blueprint $table) {
            $table->string('bill_no')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('sub_contractor_bills', function (Blueprint $table) {
            $table->dropColumn('bill_no');
        });
    }
};
