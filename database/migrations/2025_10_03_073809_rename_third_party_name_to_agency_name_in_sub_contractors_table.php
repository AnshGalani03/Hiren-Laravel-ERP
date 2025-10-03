<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameThirdPartyNameToAgencyNameInSubContractorsTable extends Migration
{
    public function up()
    {
        Schema::table('sub_contractors', function (Blueprint $table) {
            $table->renameColumn('third_party_name', 'agency_name');
        });
    }

    public function down()
    {
        Schema::table('sub_contractors', function (Blueprint $table) {
            $table->renameColumn('agency_name', 'third_party_name');
        });
    }
}
