<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation');
            $table->string('mobile_no');
            $table->string('alt_contact_no')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('aadhar_no')->nullable();
            $table->decimal('salary', 10, 2)->default(0);
            $table->decimal('pf', 10, 2)->default(0);
            $table->decimal('esic', 10, 2)->default(0);
            $table->string('bank_name')->nullable();
            $table->string('account_no')->nullable();
            $table->string('ifsc')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
