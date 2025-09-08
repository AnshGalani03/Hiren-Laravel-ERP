<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->string('work_name');
            $table->string('department');
            $table->decimal('amount_emd_fdr', 10, 2);
            $table->decimal('amount_dd', 10, 2);
            $table->string('above_below');
            $table->text('remark')->nullable();
            $table->text('return_detail')->nullable();
            $table->date('date');
            $table->string('result')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tenders');
    }
};
