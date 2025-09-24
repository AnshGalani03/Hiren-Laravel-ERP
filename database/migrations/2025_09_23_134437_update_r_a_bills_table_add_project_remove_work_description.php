<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('r_a_bills', function (Blueprint $table) {
            // Add project_id
            $table->unsignedBigInteger('project_id')->after('customer_id');
            $table->foreign('project_id')->references('id')->on('projects');
            
            // Remove work_description
            $table->dropColumn('work_description');
        });
    }

    public function down(): void
    {
        Schema::table('r_a_bills', function (Blueprint $table) {
            // Add back work_description
            $table->text('work_description')->after('customer_id');
            
            // Remove project_id
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
        });
    }
};
