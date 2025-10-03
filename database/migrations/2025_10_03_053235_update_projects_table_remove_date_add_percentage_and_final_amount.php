<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProjectsTableRemoveDateAddPercentageAndFinalAmount extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            // Remove date if it exists
            if (Schema::hasColumn('projects', 'date')) {
                $table->dropColumn('date');
            }

            // Add new fields
            $table->string('percentage', 50)->nullable()->after('amount_project');
            $table->decimal('final_project_amount', 15, 2)->nullable()->after('percentage');
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            // Remove new fields
            if (Schema::hasColumn('projects', 'percentage')) {
                $table->dropColumn('percentage');
            }
            if (Schema::hasColumn('projects', 'final_project_amount')) {
                $table->dropColumn('final_project_amount');
            }

            // Re-add project_date if needed
            $table->date('project_date')->nullable();
        });
    }
}
