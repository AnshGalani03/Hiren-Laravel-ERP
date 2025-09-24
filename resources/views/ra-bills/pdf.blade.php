<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>R.A. Bill No. {{ $raBill->bill_no }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            margin: 0; 
            padding: 15px;
        }
        
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .header h1 { 
            margin: 0; 
            font-size: 18px; 
            font-weight: bold; 
        }
        
        .info-section {
            margin-bottom: 15px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .calculation-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 15px; 
        }
        
        .calculation-table td, .calculation-table th {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        
        .calculation-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        .amount {
            text-align: right;
            font-weight: bold;
        }
        
        .total-row {
            background-color: #f9f9f9;
            font-weight: bold;
        }
        
        .net-amount {
            background-color: #e8f5e8;
            font-size: 14px;
            font-weight: bold;
        }
        
        .section-title {
            font-weight: bold;
            background-color: #f0f0f0;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>R.A. BILL</h1>
    </div>

    <!-- Bill Information -->
    <div class="info-section">
        <div class="info-row">
            <span><strong>Bill No:</strong> {{ $raBill->bill_no }}</span>
            <span><strong>Date:</strong> {{ $raBill->date ? $raBill->date->format('d/m/Y') : 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span><strong>Customer:</strong> {{ $raBill->customer->name ?? 'N/A' }}</span>
        </div>
        <div style="margin-top: 10px;">
            <strong>Work Description:</strong><br>
            {{ $raBill->work_description }}
        </div>
    </div>

    <!-- Calculation Table -->
    <table class="calculation-table">
        <thead>
            <tr>
                <th style="width: 70%;">Description</th>
                <th style="width: 30%;">Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            <!-- Section A & B -->
            <tr>
                <td>(A) R.A. Bill NO 1. including GST Amount</td>
                <td class="amount">{{ number_format($raBill->ra_bill_amount, 0) }}</td>
            </tr>
            <tr>
                <td>(B) Less: Department Taxes & Overheads</td>
                <td class="amount">{{ number_format($raBill->dept_taxes_overheads, 0) }}</td>
            </tr>
            <tr class="total-row">
                <td>(C) R.A. Bill Total (A - B)</td>
                <td class="amount">{{ number_format($raBill->total_c, 0) }}</td>
            </tr>
            
            <!-- GST Section -->
            <tr>
                <td>Add SGST @ 9.00%</td>
                <td class="amount">{{ number_format($raBill->sgst_9_percent, 0) }}</td>
            </tr>
            <tr>
                <td>Add CGST @ 9.00%</td>
                <td class="amount">{{ number_format($raBill->cgst_9_percent, 0) }}</td>
            </tr>
            <tr>
                <td>Add IGST @ 0.00%</td>
                <td class="amount">0</td>
            </tr>
            <tr class="total-row">
                <td>(D) Total With GST</td>
                <td class="amount">{{ number_format($raBill->total_with_gst, 0) }}</td>
            </tr>
            
            <!-- Deductions Section -->
            <tr class="section-title">
                <td colspan="2">(E) Deductions</td>
            </tr>
            <tr>
                <td>Less: TDS 1%</td>
                <td class="amount">{{ number_format($raBill->tds_1_percent, 0) }}</td>
            </tr>
            <tr>
                <td>Less: RMD</td>
                <td class="amount">{{ number_format($raBill->rmd_amount, 0) }}</td>
            </tr>
            <tr>
                <td>Less: Welfare Cess</td>
                <td class="amount">{{ number_format($raBill->welfare_cess, 0) }}</td>
            </tr>
            <tr>
                <td>Less: Testing Charges</td>
                <td class="amount">{{ number_format($raBill->testing_charges, 0) }}</td>
            </tr>
            <tr class="total-row">
                <td>Total Deductions</td>
                <td class="amount">{{ number_format($raBill->total_deductions, 0) }}</td>
            </tr>
            
            <!-- Net Amount -->
            <tr class="net-amount">
                <td>NET AMOUNT (D - Total Deductions)</td>
                <td class="amount">{{ number_format($raBill->net_amount, 0) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Footer -->
    <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #666;">
        Generated on {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>
