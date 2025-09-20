<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('original_amount', 12, 2)->after('amount'); // Store original amount for reference
            $table->decimal('gst_rate', 5, 2)->default(18.00)->after('original_amount'); // GST rate (18%)
            // Note: 'amount' field will store only GST amount
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['original_amount', 'gst_rate']);
        });
    }
};
