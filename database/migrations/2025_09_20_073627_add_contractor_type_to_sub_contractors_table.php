<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sub_contractors', function (Blueprint $table) {
            $table->enum('contractor_type', ['self', 'third_party'])->default('self')->after('contractor_name');
            $table->string('third_party_name')->nullable()->after('contractor_type');
            
            // Add index for better performance
            $table->index(['contractor_type', 'contractor_name']);
        });
    }

    public function down(): void
    {
        Schema::table('sub_contractors', function (Blueprint $table) {
            $table->dropIndex(['contractor_type', 'contractor_name']);
            $table->dropColumn(['contractor_type', 'third_party_name']);
        });
    }
};
