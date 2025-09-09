<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('invoices', 'remark')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->text('remark')->nullable()->after('date');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('invoices', 'remark')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropColumn('remark');
            });
        }
    }
};
