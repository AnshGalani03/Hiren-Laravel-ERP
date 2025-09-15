<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        .report-info {
            margin-bottom: 20px;
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #e0e0e0;
            font-weight: bold;
            text-align: center;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .totals-table {
            width: 50%;
            margin: 20px auto;
            border: 2px solid #333;
        }
        .totals-table th {
            background-color: #333;
            color: white;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <div class="company-name">{{ config('company.name', 'Shingala Hiren Nareshbhai') }}</div>
        <div class="report-title">{{ $title }}</div>
    </div>

    {{-- Report Information --}}
    <div class="report-info">
        <div class="info-row">
            <strong>Period:</strong>
            <span>{{ $start_date->format('d M Y') }} to {{ $end_date->format('d M Y') }}</span>
        </div>
        @if($employee)
        <div class="info-row">
            <strong>Employee:</strong>
            <span>{{ $employee->name }}</span>
        </div>
        @endif
        <div class="info-row">
            <strong>Generated:</strong>
            <span>{{ $generated_at->format('d M Y H:i A') }}</span>
        </div>
        <div class="info-row">
            <strong>Total Records:</strong>
            <span>{{ $upads->count() }}</span>
        </div>
    </div>

    {{-- Data Table --}}
    @if($upads->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="8%">Sr.</th>
                    <th width="25%">Employee</th>
                    <th width="15%">Date</th>
                    <th width="35%">Remark</th>
                    <th width="17%">Upad Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($upads as $index => $upad)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $upad->employee->name }}</td>
                    <td class="text-center">{{ $upad->date->format('d/m/Y') }}</td>
                    <td>{{ $upad->remark ?? 'No remark' }}</td>
                    <td class="text-right">₹{{ number_format($upad->upad, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Summary Totals --}}
        <table class="totals-table">
            <thead>
                <tr>
                    <th colspan="2">SUMMARY</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Total Records:</strong></td>
                    <td class="text-right"><strong>{{ $upads->count() }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Total Upad Amount:</strong></td>
                    <td class="text-right"><strong>₹{{ number_format($total_upad, 2) }}</strong></td>
                </tr>
                @if($upads->count() > 0)
                <tr>
                    <td><strong>Average per Record:</strong></td>
                    <td class="text-right"><strong>₹{{ number_format($total_upad / $upads->count(), 2) }}</strong></td>
                </tr>
                @endif
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 50px; color: #666; font-size: 14px;">
            <p><strong>No records found for the selected criteria.</strong></p>
        </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p>This is a system generated report | {{ config('company.name', 'Shingala Hiren Nareshbhai') }}</p>
    </div>
</body>
</html>
