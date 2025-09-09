<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check if 'remark' column doesn't exist before adding it
        if (!Schema::hasColumn('invoices', 'remark')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->text('remark')->nullable()->after('date');
            });
        }
        
        // Add payment status columns to upads table
        if (!Schema::hasColumn('upads', 'salary_paid')) {
            Schema::table('upads', function (Blueprint $table) {
                $table->boolean('salary_paid')->default(false)->after('salary');
            });
        }
        
        if (!Schema::hasColumn('upads', 'upad_paid')) {
            Schema::table('upads', function (Blueprint $table) {
                $table->boolean('upad_paid')->default(false)->after('upad');
            });
        }
    }

    public function down()
    {
        // Remove columns only if they exist
        if (Schema::hasColumn('invoices', 'remark')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropColumn('remark');
            });
        }
        
        if (Schema::hasColumn('upads', 'salary_paid')) {
            Schema::table('upads', function (Blueprint $table) {
                $table->dropColumn('salary_paid');
            });
        }
        
        if (Schema::hasColumn('upads', 'upad_paid')) {
            Schema::table('upads', function (Blueprint $table) {
                $table->dropColumn('upad_paid');
            });
        }
    }
};
