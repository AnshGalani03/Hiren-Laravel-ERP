<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sub_contractors', function (Blueprint $table) {
            $table->id();
            $table->string('contractor_name');
            $table->date('date');
            $table->string('project_name');
            $table->string('department_name');
            $table->decimal('amount_project', 12, 2);
            $table->string('time_limit');
            $table->text('emd_fdr_detail')->nullable();
            $table->decimal('expenses', 12, 2)->default(0);
            $table->date('work_order_date')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sub_contractors');
    }
};
