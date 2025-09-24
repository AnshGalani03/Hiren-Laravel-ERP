<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('r_a_bills', function (Blueprint $table) {
            $table->dropColumn('work_description');
        });
    }

    public function down(): void
    {
        Schema::table('r_a_bills', function (Blueprint $table) {
            $table->text('work_description')->nullable();
        });
    }
};
