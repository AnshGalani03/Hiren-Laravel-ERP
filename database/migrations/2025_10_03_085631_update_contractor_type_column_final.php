<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateContractorTypeColumnFinal extends Migration
{
    public function up()
    {
        // Step 1: Expand ENUM to include 'agency'
        DB::statement("ALTER TABLE sub_contractors MODIFY contractor_type ENUM('self', 'third_party', 'agency') NOT NULL");
        
        // Step 2: Update existing data
        DB::table('sub_contractors')
            ->where('contractor_type', 'third_party')
            ->update(['contractor_type' => 'agency']);
        
        // Step 3: Clean up ENUM (remove old option)
        DB::statement("ALTER TABLE sub_contractors MODIFY contractor_type ENUM('self', 'agency') NOT NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE sub_contractors MODIFY contractor_type ENUM('self', 'agency', 'third_party') NOT NULL");
        
        DB::table('sub_contractors')
            ->where('contractor_type', 'agency')
            ->update(['contractor_type' => 'third_party']);
            
        DB::statement("ALTER TABLE sub_contractors MODIFY contractor_type ENUM('self', 'third_party') NOT NULL");
    }
}
