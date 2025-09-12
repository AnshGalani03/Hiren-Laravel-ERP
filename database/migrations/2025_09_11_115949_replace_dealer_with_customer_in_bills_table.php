<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bills', function (Blueprint $table) {
            // Drop foreign key and dealer_id column
            $table->dropForeign(['dealer_id']);
            $table->dropColumn('dealer_id');

            // Add customer_id column with foreign key
            $table->unsignedBigInteger('customer_id')->nullable()->after('bill_number');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('bills', function (Blueprint $table) {
            // Drop foreign key and customer_id column
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');

            // Add back dealer_id column with foreign key
            $table->unsignedBigInteger('dealer_id')->nullable()->after('bill_number');
            $table->foreign('dealer_id')->references('id')->on('dealers')->onDelete('set null');
        });
    }
};
