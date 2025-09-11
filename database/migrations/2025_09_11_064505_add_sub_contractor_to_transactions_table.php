<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubContractorToTransactionsTable extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_contractor_id')->nullable()->after('dealer_id');
            $table->foreign('sub_contractor_id')->references('id')->on('sub_contractors')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['sub_contractor_id']);
            $table->dropColumn('sub_contractor_id');
        });
    }
}
