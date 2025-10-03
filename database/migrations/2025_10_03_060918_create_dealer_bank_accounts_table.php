<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealerBankAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('dealer_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_id')->constrained()->onDelete('cascade');
            $table->string('account_name');
            $table->string('account_no');
            $table->string('bank_name');
            $table->string('ifsc');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dealer_bank_accounts');
    }
}
