<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            // Change PF from decimal to string for PF Number
            $table->string('pf')->nullable()->change();
            
            // Change ESIC from decimal to string for ESIC Number  
            $table->string('esic')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            // Revert back to decimal if needed
            $table->decimal('pf', 10, 2)->default(0)->change();
            $table->decimal('esic', 10, 2)->default(0)->change();
        });
    }
};
