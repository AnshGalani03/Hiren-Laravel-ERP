<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RA Bill {{ $raBill->bill_no }}</title>
    <style>
        @page {
            margin: 50mm 8mm 8mm 8mm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            font-size: 11px;
            line-height: 1.2;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
            margin-bottom: 0;
        }

        .header-table td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }

        .bill-header {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            background-color: #f0f0f0;
        }

        .customer-section {
            font-size: 11px;
        }

        .customer-name {
            font-weight: bold;
            font-size: 12px;
        }

        .work-description {
            /* text-align: center; */
            font-weight: bold;
            background-color: #f5f5f5;
            font-size: 11px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
            margin-top: 0;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            vertical-align: middle;
            font-size: 10px;
        }

        .main-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            font-size: 11px;
        }

        .sr-no-column {
            width: 10%;
            text-align: center;
            font-weight: bold;
        }

        .description-column {
            width: 55%;
        }

        .amount-column {
            width: 15%;
            text-align: right;
            font-size: 10px;
        }

        .rs-column {
            width: 20%;
            text-align: right;
            font-weight: bold;
            font-size: 10px;
        }

        .section-row {
            background-color: #f8f8f8;
            font-weight: bold;
        }

        .total-row {
            background-color: #e6e6e6;
            font-weight: bold;
        }

        .net-amount-row {
            background-color: #d0d0d0;
            font-weight: bold;
            font-size: 11px;
        }

        .deduction-header {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .footer-info {
            margin-top: 10px;
            font-size: 10px;
            border: 1px solid #000;
            padding: 5px;
        }

        .indent {
            padding-left: 20px;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- Header Section -->
    <table class="header-table">
        <tr>
            <td class="bill-header" colspan="4">
                Bill NO. {{ $raBill->bill_no }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date: {{ $raBill->date->format('d/m/Y') }}
            </td>
        </tr>
        <tr>
            <td colspan="4" style="height: 5px;"></td>
        </tr>
        <tr>
            <td class="customer-section" colspan="4">
                <strong>To</strong>
            </td>
        </tr>
        <tr>
            <td class="customer-section" colspan="4">
                <span class="customer-name" style="padding-left: 20px;">{{ $raBill->customer->name ?? 'NA' }}</span>
            </td>
        </tr>
        <tr>
            <td class="customer-section" colspan="4">
                {{ $raBill->customer->address ?? 'NA' }}
            </td>
        </tr>
        <tr>
            <td class="customer-section" colspan="4">
                <strong>GST No. :-</strong>{{ $raBill->customer->gst ?? 'NA' }}
            </td>
        </tr>
        <tr>
            <td class="customer-section" colspan="4">
                <strong>PAN No:-</strong>{{ $raBill->customer->pan_card ?? 'NA' }}
            </td>
        </tr>
        <tr>
            <td colspan="4" style="height: 5px;"></td>
        </tr>
        <tr>
            <td class="work-description" colspan="4">
                <strong>Name of Work :</strong> {{ $raBill->project->name ?? 'NA' }}
            </td>
        </tr>
    </table>

    <!-- Main Bill Table -->
    <table class="main-table">
        <thead>
            <tr>
                <th class="sr-no-column">Sr. No.</th>
                <th class="description-column">Description</th>
                <th class="amount-column">AMOUNT</th>
                <th class="rs-column">RS.</th>
            </tr>
        </thead>
        <tbody>
            <!-- Section A -->
            <tr>
                <td class="sr-no-column">(A)</td>
                <td>R.A. Bill NO 1. including GST Amount.</td>
                <td class="amount-column">{{ number_format($raBill->ra_bill_amount ?? 'NA', 0) }}</td>
                <td class="rs-column"></td>
            </tr>

            <!-- Section B -->
            <tr>
                <td class="sr-no-column">(B)</td>
                <td>Less : Department Taxes & Overheads</td>
                <td class="amount-column">{{ number_format($raBill->dept_taxes_overheads ?? 'NA', 0) }}</td>
                <td class="rs-column"></td>
            </tr>

            <!-- Section C -->
            <tr class="section-row">
                <td class="sr-no-column">(C)</td>
                <td>R.A Bill NO 1. for Shingala Hiren Nareshbhai Total (C ) (A - B )</td>
                <td class="amount-column">{{ number_format($raBill->total_c ?? 'NA', 0) }}</td>
                <td class="rs-column bold">{{ number_format($raBill->total_c ?? 'NA', 0) }}</td>
            </tr>

            <!-- GST Additions -->
            <tr>
                <td class="sr-no-column"></td>
                <td class="indent">Add SGST @ 9.00%</td>
                <td class="amount-column">{{ number_format($raBill->sgst_9_percent ?? 'NA', 0) }}</td>
                <td class="rs-column"></td>
            </tr>
            <tr>
                <td class="sr-no-column"></td>
                <td class="indent">Add CGST @ 9.00%</td>
                <td class="amount-column">{{ number_format($raBill->cgst_9_percent ?? 'NA', 0) }}</td>
                <td class="rs-column"></td>
            </tr>
            <tr>
                <td class="sr-no-column"></td>
                <td class="indent">Add IGST @ 0.00%</td>
                <td class="amount-column">0</td>
                <td class="rs-column"></td>
            </tr>

            <!-- Section D -->
            <tr class="section-row">
                <td class="sr-no-column">(D)</td>
                <td>Total With GST</td>
                <td class="amount-column"></td>
                <td class="rs-column bold">{{ number_format($raBill->total_with_gst ?? 'NA', 0) }}</td>
            </tr>

            <!-- Section E - Deductions -->
            <tr class="deduction-header">
                <td class="sr-no-column">(E)</td>
                <td><strong>Deduction</strong></td>
                <td class="amount-column"></td>
                <td class="rs-column"></td>
            </tr>
            <tr>
                <td class="sr-no-column"></td>
                <td class="indent">Less : TDS 1%</td>
                <td class="amount-column">{{ number_format($raBill->tds_1_percent ?? 'NA', 0) }}</td>
                <td class="rs-column"></td>
            </tr>
            <tr>
                <td class="sr-no-column"></td>
                <td class="indent">Less : RMD</td>
                <td class="amount-column">{{ number_format($raBill->rmd_amount ?? 'NA', 0) }}</td>
                <td class="rs-column"></td>
            </tr>
            <tr>
                <td class="sr-no-column"></td>
                <td class="indent">Less : Welfare Cess</td>
                <td class="amount-column">{{ number_format($raBill->welfare_cess ?? 'NA', 0) }}</td>
                <td class="rs-column"></td>
            </tr>
            <tr>
                <td class="sr-no-column"></td>
                <td class="indent">Less : Testing Chrges</td>
                <td class="amount-column">{{ number_format($raBill->testing_charges ?? 'NA', 0) }}</td>
                <td class="rs-column"></td>
            </tr>

            <!-- Empty rows for spacing -->
            <tr>
                <td class="sr-no-column"></td>
                <td></td>
                <td class="amount-column"></td>
                <td class="rs-column"></td>
            </tr>

            <!-- Total Deductions -->
            <tr class="total-row">
                <td class="sr-no-column"></td>
                <td style="text-align: right; padding-right: 10px;"><strong>Total (D)</strong></td>
                <td class="amount-column">{{ number_format($raBill->total_deductions ?? 'NA', 0) }}</td>
                <td class="rs-column bold"></td>
            </tr>

            <!-- Repeat Total in Amount Column -->
            <tr>
                <td class="sr-no-column"></td>
                <td></td>
                <td class="amount-column"></td>
                <td class="rs-column bold">{{ number_format($raBill->total_deductions ?? 178015, 0) }}</td>
            </tr>

            <!-- Net Amount -->
            <tr class="net-amount-row">
                <td class="sr-no-column"></td>
                <td><strong>NET AMOUNT( D - E ) Payable to Shingala Hiren Nareshbhai</strong></td>
                <td class="amount-column"></td>
                <td class="rs-column bold">{{ number_format($raBill->net_amount ?? 'NA', 0) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer-info">
        <div style="margin-bottom: 10px;">
            <strong>PAN :</strong> GYTPS4723P
        </div>
        <div style="margin-bottom: 10px;">
            <strong>GST:-</strong> 24GYTPS4723P1ZC
        </div>
        <div style="clear: both;"></div>
    </div>
</body>

</html>