<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $invoice->filename}}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css" media="screen">
        @page {
            margin: 8mm 8mm 8mm 8mm;
        }

        * {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            box-sizing: border-box;
        }

        body {
            font-size: 11px;
            line-height: 1.2;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .invoice-container {
            max-width: 100%;
            margin: 0;
            border: 1px solid #000;
            padding: 8px;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .company-info {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }

        .company-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .company-address {
            font-size: 10px;
            color: #666;
            line-height: 1.1;
        }

        .invoice-title {
            display: table-cell;
            width: 40%;
            text-align: right;
            vertical-align: top;
        }

        .invoice-title h1 {
            font-size: 32px;
            color: #1e3a8a;
            font-weight: bold;
            margin: 0;
        }

        .invoice-details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            border: 1px solid #000;
        }

        .invoice-details-table td {
            border: 1px solid #000;
            padding: 3px 6px;
            font-size: 10px;
        }

        .invoice-details-table .label {
            background: #f5f5f5;
            font-weight: bold;
            width: 80px;
        }

        .party-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            border: 1px solid #000;
        }

        .party-table th {
            background: #f5f5f5;
            border: 1px solid #000;
            padding: 4px;
            font-weight: bold;
            text-align: left;
            font-size: 11px;
        }

        .party-table td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
            font-size: 10px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            border: 1px solid #000;
        }

        .items-table th {
            background: #1e3a8a;
            color: white;
            border: 1px solid #000;
            padding: 4px 3px;
            font-weight: bold;
            font-size: 10px;
        }

        .items-table td {
            border: 1px solid #000;
            padding: 4px 3px;
            font-size: 10px;
        }

        .item-description {
            font-weight: bold;
            font-size: 10px;
        }

        .item-subtitle {
            font-size: 9px;
            color: #666;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .summary-section {
            float: right;
            width: 200px;
            margin-top: 5px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 4px 8px;
            font-size: 11px;
            font-weight: bold;
        }

        .summary-table .subtotal-row {
            background: #dde7ff;
        }

        .summary-table .tax-row {
            background: #c2d6ff;
        }

        .summary-table .total-row {
            background: #a6c8ff;
        }

        .terms-section {
            clear: both;
            margin-top: 20px;
            font-size: 9px;
            color: #666;
        }

        .terms-title {
            font-weight: bold;
            color: #000;
            margin-bottom: 3px;
        }

        .thank-you {
            font-size: 10px;
            margin-bottom: 5px;
            color: #666;
        }
    </style>
</head>

<body>
    {{-- Determine if it's GST bill based on tax rate --}}
    @php
    $isGstBill = ($invoice->tax_rate ?? 0) > 0;
    // Get the original bill data that we shared from controller
    $originalBill = $originalBill ?? null;

    // If originalBill is not available, try to get it from the sequence
    if (!$originalBill) {
    $billId = $invoice->getCustomData()['sequence'] ?? 0;
    if ($billId) {
    $originalBill = \App\Models\Bill::with('billItems.product')->find($billId);
    }
    }
    @endphp
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                @if($invoice->logo)
                <!-- <img src="{{ $invoice->getLogo() }}" alt="Logo" style="max-width: 80px; margin-bottom: 5px;"> -->
                @endif
                <div class="company-name">{{ $invoice->seller->name }}</div>
                <div class="company-address">
                    {{ $invoice->seller->address }}<br>
                    @if($invoice->seller->phone)
                    {{ $invoice->seller->phone }}<br>
                    @endif
                    @if($isGstBill)
                    @foreach($invoice->seller->custom_fields as $key => $value)
                    @if($value)
                    {{ ucfirst($key) }}: {{ $value }}<br>
                    @endif
                    @endforeach
                    @endif
                </div>
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
            </div>
        </div>

        <!-- Invoice Details -->
        <table class="invoice-details-table">
            <tr>
                <td class="label">Invoice#</td>
                <td>{{ $invoice->getSerialNumber() }}</td>
                <td class="label">Invoice Date</td>
                <td>{{ $invoice->getDate() }}</td>
            </tr>
        </table>

        <!-- Bill To -->
        <table class="party-table">
            <thead>
                <tr>
                    <th>Bill To</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $invoice->buyer->name }}</strong><br>
                        {{ $invoice->buyer->address }}<br>
                        @if($invoice->buyer->phone)
                        {{ $invoice->buyer->phone }}<br>
                        @endif
                        @if($isGstBill)
                        @foreach($invoice->buyer->custom_fields as $key => $value)
                        @if($value)
                        {{ ucfirst($key) }}: {{ $value }}<br>
                        @endif
                        @endforeach
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 30%">Item & Description</th>
                    <th style="width: 15%">HSN Code</th>
                    <th style="width: 10%" class="text-right">Qty</th>
                    <th style="width: 15%" class="text-right">Rate</th>
                    <th style="width: 15%" class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div class="item-description">{{ $item->title }}</div>
                    </td>
                    <td class="text-center">
                        {{-- Get HSN code from original bill items --}}
                        @php
                        $hsnCode = 'N/A';
                        if ($originalBill && $originalBill->billItems && isset($originalBill->billItems[$index])) {
                        $billItem = $originalBill->billItems[$index];
                        if ($billItem->product && $billItem->product->hsn_code) {
                        $hsnCode = $billItem->product->hsn_code;
                        }
                        }
                        @endphp
                        {{ $hsnCode }}
                    </td>
                    <td class="text-center">{{ number_format($item->quantity, 2) }}</td>
                    <td class="text-right">{{ $invoice->formatCurrency(floatval($item->price_per_unit ?? 0)) }}</td>
                    <td class="text-right">{{ $invoice->formatCurrency(floatval($item->sub_total_price ?? 0)) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Thank You Message -->
        <div class="thank-you">
            Thanks for shopping with us.
        </div>

        <div class="summary-section">
            <table class="summary-table">


                <tr class="subtotal-row">
                    <td>Subtotal</td>
                    <td class="text-right">₹{{ number_format($originalBill->subtotal, 2) }}</td>
                </tr>

                @if($isGstBill)
                <tr class="tax-amount-row">
                    <td>GST @ {{ number_format($originalBill->tax_rate, 2) }} %</td>
                    <td class="text-right">₹{{ number_format($originalBill->tax_amount, 2) }}</td>
                </tr>
                @endif

                <tr class="total-row" style="border-top: 2px solid #333; font-weight: bold;">
                    <td><strong>Grand Total</strong></td>
                    <td class="text-right"><strong>₹{{ number_format($originalBill->total_amount, 2) }}</strong></td>
                </tr>
            </table>
        </div>




        <!-- Terms & Conditions -->
        <div class="terms-section">
            <div class="terms-title">Terms & Conditions Apply</div>
            @if($invoice->notes)
            <p>{{ $invoice->notes }}</p>
            @else
            <!-- <p>Full payment is due upon receipt of this invoice.</p> -->
            @endif
        </div>
    </div>
</body>

</html>