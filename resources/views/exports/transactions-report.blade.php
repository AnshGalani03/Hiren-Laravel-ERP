<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $title ?? 'Transactions Report' }}</title>
    <style>
        @page {
            margin: 10px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 7px;
            line-height: 1.2;
            margin: 0;
            padding: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }

        .company-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .report-title {
            font-size: 12px;
            font-weight: bold;
            color: #333;
        }

        .report-info {
            margin-bottom: 10px;
            background-color: #f5f5f5;
            padding: 5px;
            border-radius: 2px;
            font-size: 7px;
        }

        .info-row {
            margin-bottom: 2px;
            display: inline-block;
            width: 48%;
            vertical-align: top;
        }

        .info-row strong {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 7px;
        }

        th,
        td {
            border: 0.5px solid #666;
            padding: 2px;
            text-align: left;
            vertical-align: middle;
            word-wrap: break-word;
        }

        th {
            background-color: #333;
            color: white;
            font-weight: bold;
            text-align: center;
            font-size: 7px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .incoming {
            color: #16a34a;
            font-weight: bold;
        }

        .outgoing {
            color: #dc2626;
            font-weight: bold;
        }

        .totals-row {
            border-top: 2px solid #333;
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .summary-table {
            width: 50%;
            margin: 10px auto;
            border: 1.5px solid #333;
        }

        .summary-table th {
            background-color: #333;
            color: white;
            padding: 4px;
            font-size: 8px;
        }

        .summary-table td {
            padding: 3px;
            font-size: 7px;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 6px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #666;
            font-size: 10px;
        }

        /* Column widths */
        .col-sr {
            width: 3%;
        }

        .col-date {
            width: 7%;
        }

        .col-type {
            width: 7%;
        }

        .col-project {
            width: 9%;
        }

        .col-dealer {
            width: 9%;
        }

        .col-subcon {
            width: 9%;
        }

        .col-customer {
            width: 9%;
        }

        .col-employee {
            width: 9%;
        }

        .col-category {
            width: 10%;
        }

        .col-desc {
            width: 13%;
        }

        .col-income {
            width: 7.5%;
        }

        .col-outgo {
            width: 7.5%;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <div class="header">
        <div class="company-name">{{ config('company.name', 'Shingala Hiren Nareshbhai') }}</div>
        <div class="report-title">{{ $title ?? 'Transactions Report' }}</div>
    </div>

    {{-- Report Information --}}
    <div class="report-info">
        <div class="info-row">
            <strong>Period:</strong>
            {{ isset($start_date) ? $start_date->format('d M Y') : 'N/A' }} to {{ isset($end_date) ? $end_date->format('d M Y') : 'N/A' }}
        </div>
        <div class="info-row">
            <strong>Generated:</strong>
            {{ isset($generated_at) ? $generated_at->format('d M Y h:i A') : now()->format('d M Y h:i A') }}
        </div>
        @if(isset($type) && $type)
        <div class="info-row">
            <strong>Type:</strong> {{ ucfirst($type) }}
        </div>
        @endif
        @if(isset($project) && $project)
        <div class="info-row">
            <strong>Project:</strong> {{ $project->name }}
        </div>
        @endif
        @if(isset($dealer) && $dealer)
        <div class="info-row">
            <strong>Dealer:</strong> {{ $dealer->dealer_name }}
        </div>
        @endif
        @if(isset($sub_contractor) && $sub_contractor)
        <div class="info-row">
            <strong>Sub-Contractor:</strong> {{ $sub_contractor->contractor_name }}
        </div>
        @endif
        @if(isset($customer) && $customer)
        <div class="info-row">
            <strong>Customer:</strong> {{ $customer->name }}
        </div>
        @endif
        @if(isset($employee) && $employee)
        <div class="info-row">
            <strong>Employee:</strong> {{ $employee->name }}
        </div>
        @endif
        <div class="info-row">
            <strong>Total Records:</strong> {{ isset($transactions) ? $transactions->count() : 0 }}
        </div>
    </div>

    {{-- Data Table --}}
    @if(isset($transactions) && $transactions->count() > 0)
    <table>
        <thead>
            <tr>
                <th class="col-sr">Sr.</th>
                <th class="col-date">Date</th>
                <th class="col-type">Type</th>
                <th class="col-project">Project</th>
                <th class="col-dealer">Dealer</th>
                <th class="col-subcon">Sub-Con</th>
                <th class="col-customer">Customer</th>
                <th class="col-employee">Employee</th>
                <th class="col-category">Category</th>
                <th class="col-desc">Description</th>
                <th class="col-income">Incoming</th>
                <th class="col-outgo">Outgoing</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $index => $transaction)
            @php
            $amount = floatval($transaction->amount ?? 0);
            $type = strtolower($transaction->type ?? '');
            $isIncoming = ($type === 'incoming');

            // Get category
            $category = '-';
            if ($isIncoming && $transaction->incoming) {
            $category = $transaction->incoming->name;
            } elseif (!$isIncoming && $transaction->outgoing) {
            $category = $transaction->outgoing->name;
            }

            // Get names with proper truncation
            $projectName = $transaction->project ? $transaction->project->name : '-';
            $dealerName = $transaction->dealer ? $transaction->dealer->dealer_name : '-';
            $subContractorName = $transaction->subContractor ? $transaction->subContractor->contractor_name : '-';
            $customerName = $transaction->customer ? $transaction->customer->name : '-';
            $employeeName = $transaction->employee ? $transaction->employee->name : '-';
            $description = $transaction->description ?? '-';

            // Truncate long texts
            if (strlen($category) > 12) $category = substr($category, 0, 12) . '..';
            if (strlen($projectName) > 12) $projectName = substr($projectName, 0, 12) . '..';
            if (strlen($dealerName) > 12) $dealerName = substr($dealerName, 0, 12) . '..';
            if (strlen($subContractorName) > 12) $subContractorName = substr($subContractorName, 0, 10) . '..';
            if (strlen($customerName) > 12) $customerName = substr($customerName, 0, 12) . '..';
            if (strlen($employeeName) > 12) $employeeName = substr($employeeName, 0, 12) . '..';
            if (strlen($description) > 18) $description = substr($description, 0, 18) . '..';
            @endphp
            <tr>
                <td class="text-center col-sr">{{ $index + 1 }}</td>
                <td class="text-center col-date">
                    {{ isset($transaction->date) ? $transaction->date->format('d/m/Y') : '-' }}
                </td>
                <td class="text-center col-type">
                    <span class="{{ $isIncoming ? 'incoming' : 'outgoing' }}">
                        {{ $isIncoming ? 'Incoming' : 'Outgoing' }}
                    </span>
                </td>
                <td class="col-project">{{ $projectName }}</td>
                <td class="col-dealer">{{ $dealerName }}</td>
                <td class="col-subcon">{{ $subContractorName }}</td>
                <td class="col-customer">{{ $customerName }}</td>
                <td class="col-employee">{{ $employeeName }}</td>
                <td class="col-category">{{ $category }}</td>
                <td class="col-desc">{{ $description }}</td>
                <td class="text-right col-income">
                    @if($isIncoming)
                    <span class="incoming">₹{{ number_format($amount, 0) }}</span>
                    @else
                    -
                    @endif
                </td>
                <td class="text-right col-outgo">
                    @if(!$isIncoming)
                    <span class="outgoing">₹{{ number_format($amount, 0) }}</span>
                    @else
                    -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="totals-row">
                <td colspan="10" class="text-right"><strong>TOTALS:</strong></td>
                <td class="text-right">
                    <strong class="incoming">₹{{ number_format($total_incoming ?? 0, 0) }}</strong>
                </td>
                <td class="text-right">
                    <strong class="outgoing">₹{{ number_format($total_outgoing ?? 0, 0) }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>

    {{-- Summary Table --}}
    <table class="summary-table">
        <thead>
            <tr>
                <th colspan="2">FINANCIAL SUMMARY</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="incoming"><strong>Total Incoming:</strong></td>
                <td class="text-right incoming"><strong>₹{{ number_format($total_incoming ?? 0, 2) }}</strong></td>
            </tr>
            <tr>
                <td class="outgoing"><strong>Total Outgoing:</strong></td>
                <td class="text-right outgoing"><strong>₹{{ number_format($total_outgoing ?? 0, 2) }}</strong></td>
            </tr>
            <tr style="border-top: 1.5px solid #333; background-color: #e5e5e5;">
                <td><strong>Net Amount:</strong></td>
                <td class="text-right">
                    <strong style="color: {{ ($net_amount ?? 0) >= 0 ? '#16a34a' : '#dc2626' }};">
                        ₹{{ number_format($net_amount ?? 0, 2) }}
                    </strong>
                </td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td class="text-right">
                    <strong style="color: {{ ($net_amount ?? 0) >= 0 ? '#16a34a' : '#dc2626' }};">
                        {{ ($net_amount ?? 0) >= 0 ? 'PROFIT' : 'LOSS' }}
                    </strong>
                </td>
            </tr>
        </tbody>
    </table>

    @else
    <div class="no-data">
        <p><strong>No transactions found for the selected criteria.</strong></p>
        <p>Please try different date range or filters.</p>
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p>This is a system generated report | {{ config('company.name', 'Shingala Hiren Nareshbhai') }} | Generated on {{ now()->format('d M Y h:i A') }}</p>
    </div>
</body>

</html>