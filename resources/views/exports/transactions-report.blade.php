<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $title ?? 'Transactions Report' }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            line-height: 1.3;
            margin: 0;
            padding: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }

        .report-info {
            margin-bottom: 15px;
            background-color: #f9f9f9;
            padding: 8px;
            border-radius: 3px;
            font-size: 9px;
        }

        .info-row {
            margin-bottom: 3px;
        }

        .info-row strong {
            display: inline-block;
            width: 100px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 3px;
            text-align: left;
            vertical-align: top;
            font-size: 8px;
        }

        th {
            background-color: #e0e0e0;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .incoming {
            color: #22c55e;
            font-weight: bold;
        }

        .outgoing {
            color: #ef4444;
            font-weight: bold;
        }

        .totals-table {
            width: 60%;
            margin: 15px auto;
            border: 2px solid #333;
        }

        .totals-table th {
            background-color: #333;
            color: white;
        }

        .totals-table td {
            font-size: 10px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 8px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 12px;
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
        @if(isset($type) && $type)
        <div class="info-row">
            <strong>Type Filter:</strong>
            {{ ucfirst($type) }} Only
        </div>
        @endif
        @if(isset($project) && $project)
        <div class="info-row">
            <strong>Project:</strong>
            {{ $project->name }}
        </div>
        @endif
        @if(isset($dealer) && $dealer)
        <div class="info-row">
            <strong>Dealer:</strong>
            {{ $dealer->dealer_name }}
        </div>
        @endif
        @if(isset($sub_contractor) && $sub_contractor)
        <div class="info-row">
            <strong>Sub-Contractor:</strong>
            {{ $sub_contractor->contractor_name }}
        </div>
        @endif
        <div class="info-row">
            <strong>Generated:</strong>
            {{ isset($generated_at) ? $generated_at->format('d M Y H:i A') : now()->format('d M Y H:i A') }}
        </div>
        <div class="info-row">
            <strong>Total Records:</strong>
            {{ isset($transactions) ? $transactions->count() : 0 }}
        </div>
    </div>

    {{-- Data Table --}}
    @if(isset($transactions) && $transactions->count() > 0)
    <table>
        <thead>
            <tr>
                <th width="4%">Sr.</th>
                <th width="8%">Date</th>
                <th width="10%">Type</th>
                <th width="12%">Project</th>
                <th width="12%">Dealer</th>
                <th width="12%">Sub-Contractor</th>
                <th width="12%">Category</th>
                <th width="15%">Description</th>
                <th width="7.5%">Incoming</th>
                <th width="7.5%">Outgoing</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $index => $transaction)
            @php
            $amount = floatval($transaction->amount ?? 0);
            $type = strtolower($transaction->type ?? '');
            $isIncoming = ($type === 'incoming');

            // Get category from relationship
            $category = 'General';
            if ($isIncoming && $transaction->incoming) {
            $category = $transaction->incoming->name;
            } elseif (!$isIncoming && $transaction->outgoing) {
            $category = $transaction->outgoing->name;
            }

            // Get names
            $projectName = $transaction->project ? $transaction->project->name : '-';
            $dealerName = $transaction->dealer ? $transaction->dealer->dealer_name : '-'; // Changed to dealer_name
            $subContractorName = $transaction->subContractor ? $transaction->subContractor->contractor_name : '-'; // Changed to contractor_name

            // Truncate long text
            if (strlen($category) > 10) {
            $category = substr($category, 0, 10) . '...';
            }
            if (strlen($projectName) > 10) {
            $projectName = substr($projectName, 0, 10) . '...';
            }
            if (strlen($dealerName) > 10) {
            $dealerName = substr($dealerName, 0, 10) . '...';
            }
            if (strlen($subContractorName) > 10) {
            $subContractorName = substr($subContractorName, 0, 10) . '...';
            }
            $description = $transaction->description ?? 'No description';
            if (strlen($description) > 15) {
            $description = substr($description, 0, 15) . '...';
            }
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">
                    {{ isset($transaction->date) ? $transaction->date->format('d/m/Y') : 'N/A' }}
                </td>
                <td class="text-center">
                    <span class="{{ $isIncoming ? 'incoming' : 'outgoing' }}">
                        {{ ucfirst($type) }}
                    </span>
                </td>
                <td>{{ $projectName }}</td>
                <td>{{ $dealerName }}</td>
                <td>{{ $subContractorName }}</td>
                <td>{{ $category }}</td>
                <td>{{ $description }}</td>
                <td class="text-right">
                    @if($isIncoming)
                    <span class="incoming">₹{{ number_format($amount, 0) }}</span>
                    @else
                    <span style="color: #ccc;">-</span>
                    @endif
                </td>
                <td class="text-right">
                    @if(!$isIncoming)
                    <span class="outgoing">₹{{ number_format($amount, 0) }}</span>
                    @else
                    <span style="color: #ccc;">-</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="border-top: 2px solid #333; background-color: #f5f5f5;">
                <td colspan="8" class="text-right"><strong>TOTALS:</strong></td>
                <td class="text-right">
                    <strong class="incoming">₹{{ number_format($total_incoming ?? 0, 0) }}</strong>
                </td>
                <td class="text-right">
                    <strong class="outgoing">₹{{ number_format($total_outgoing ?? 0, 0) }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>

    {{-- Summary Totals --}}
    <table class="totals-table">
        <thead>
            <tr>
                <th colspan="2">FINANCIAL SUMMARY</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="incoming">Total Incoming:</td>
                <td class="text-right incoming">₹{{ number_format($total_incoming ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td class="outgoing">Total Outgoing:</td>
                <td class="text-right outgoing">₹{{ number_format($total_outgoing ?? 0, 2) }}</td>
            </tr>
            <tr style="border-top: 2px solid #333; background-color: #f0f0f0;">
                <td><strong>Net Amount:</strong></td>
                <td class="text-right">
                    <strong style="color: {{ ($net_amount ?? 0) >= 0 ? '#22c55e' : '#ef4444' }}">
                        ₹{{ number_format($net_amount ?? 0, 2) }}
                    </strong>
                </td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td class="text-right">
                    <strong style="color: {{ ($net_amount ?? 0) >= 0 ? '#22c55e' : '#ef4444' }}">
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
        <p>This is a system generated report | {{ config('company.name', 'Shingala Hiren Nareshbhai') }}</p>
    </div>
</body>

</html>