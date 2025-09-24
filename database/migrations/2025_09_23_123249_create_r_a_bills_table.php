<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('r_a_bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no');
            $table->date('date');
            $table->unsignedBigInteger('customer_id');
            $table->text('work_description');
            
            // Manual Input Fields
            $table->decimal('ra_bill_amount', 15, 2); // A: R.A. Bill Amount
            $table->decimal('dept_taxes_overheads', 15, 2); // B: Department Taxes & Overheads
            $table->decimal('tds_1_percent', 15, 2); // TDS 1%
            $table->decimal('rmd_amount', 15, 2); // RMD
            $table->decimal('welfare_cess', 15, 2); // Welfare Cess
            $table->decimal('testing_charges', 15, 2); // Testing Charges
            
            // Auto-Calculated Fields
            $table->decimal('total_c', 15, 2); // C = A - B
            $table->decimal('sgst_9_percent', 15, 2); // SGST 9%
            $table->decimal('cgst_9_percent', 15, 2); // CGST 9%
            $table->decimal('igst_0_percent', 15, 2)->default(0); // IGST 0%
            $table->decimal('total_with_gst', 15, 2); // D: Total With GST
            $table->decimal('total_deductions', 15, 2); // Total Deductions
            $table->decimal('net_amount', 15, 2); // Net Amount
            
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('r_a_bills');
    }
};
