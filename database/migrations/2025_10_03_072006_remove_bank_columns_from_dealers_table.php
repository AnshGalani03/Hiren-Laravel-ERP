<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveBankColumnsFromDealersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dealers', function (Blueprint $table) {
            // Remove the old bank-related columns
            $table->dropColumn([
                'account_no',
                'account_name', 
                'ifsc',
                'bank_name'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dealers', function (Blueprint $table) {
            // Add back the columns if we need to rollback
            $table->string('account_no')->nullable();
            $table->string('account_name')->nullable();
            $table->string('ifsc')->nullable();
            $table->string('bank_name')->nullable();
        });
    }
}
