<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Product;
use App\Models\Dealer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class BillController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $bills = Bill::query()->with('dealer');

            // Filter by dealer name
            if ($request->has('dealer_name') && !empty($request->dealer_name)) {
                $bills->whereHas('dealer', function ($q) use ($request) {
                    $q->where('dealer_name', 'like', '%' . $request->dealer_name . '%');
                });
            }

            // Filter by status
            if ($request->has('status') && !empty($request->status)) {
                $bills->where('status', $request->status);
            }

            // Filter by date range
            if ($request->has('start_date') && !empty($request->start_date)) {
                $bills->whereDate('bill_date', '>=', $request->start_date);
            }

            if ($request->has('end_date') && !empty($request->end_date)) {
                $bills->whereDate('bill_date', '<=', $request->end_date);
            }

            return DataTables::eloquent($bills)
                ->addIndexColumn()
                ->editColumn('bill_date', function ($bill) {
                    return $bill->bill_date->format('d/m/Y');
                })
                ->addColumn('dealer_name', function ($bill) {
                    return $bill->dealer->dealer_name ?? 'N/A';
                })
                ->editColumn('total_amount', function ($bill) {
                    return '₹' . number_format($bill->total_amount, 2);
                })
                ->addColumn('status', function ($bill) {
                    $badgeClass = $bill->status === 'paid' ? 'bg-success' : ($bill->status === 'sent' ? 'bg-warning' : 'bg-secondary');
                    return '
                        <div class="d-flex align-items-center">
                            <span class="badge ' . $badgeClass . ' status-badge me-2">' . ucfirst($bill->status) . '</span>
                            <select class="form-select form-select-sm status-select" data-id="' . $bill->id . '">
                                <option value="draft" ' . ($bill->status === 'draft' ? 'selected' : '') . '>Draft</option>
                                <option value="sent" ' . ($bill->status === 'sent' ? 'selected' : '') . '>Sent</option>
                                <option value="paid" ' . ($bill->status === 'paid' ? 'selected' : '') . '>Paid</option>
                            </select>
                        </div>
                    ';
                })
                ->addColumn('action', function ($bill) {
                    return '
                        <div class="action-btn">
                            <a href="' . route('bills.show', $bill->id) . '" class="btn btn-info btn-sm">View</a>
                            <a href="' . route('bills.edit', $bill->id) . '" class="btn btn-warning btn-sm">Edit</a>
                            <a href="' . route('bills.pdf', ['bill' => $bill->id, 'type' => 'gst']) . '" class="btn btn-success btn-sm">GST PDF</a>
                            <a href="' . route('bills.pdf', ['bill' => $bill->id, 'type' => 'non-gst']) . '" class="btn btn-primary btn-sm">Non-GST PDF</a>
                            <button class="btn btn-danger btn-sm delete-bill" data-id="' . $bill->id . '">Delete</button>
                        </div>
                    ';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        // Get dealers for filter dropdown
        $dealers = Dealer::orderBy('dealer_name')->get();

        return view('bills.index', compact('dealers'));
    }



    public function create()
    {
        $dealers = Dealer::orderBy('dealer_name')->get();
        $products = Product::orderBy('product_name')->get();

        return view('bills.create', compact('dealers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dealer_id' => 'required|exists:dealers,id',
            'bill_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:draft,sent,paid',
            'notes' => 'nullable|string|max:1000'
        ]);

        $bill = Bill::create([
            'dealer_id' => $request->dealer_id,
            'bill_date' => $request->bill_date,
            'tax_rate' => $request->tax_rate ?? 0,
            'notes' => $request->notes,
            'is_gst' => ($request->tax_rate ?? 0) > 0,
            'status' => $request->status
        ]);

        $subtotal = 0;

        foreach ($request->items as $item) {
            $totalPrice = $item['quantity'] * $item['unit_price'];

            BillItem::create([
                'bill_id' => $bill->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $totalPrice
            ]);

            $subtotal += $totalPrice;
        }

        $taxAmount = ($subtotal * ($request->tax_rate ?? 0)) / 100;
        $totalAmount = $subtotal + $taxAmount;

        $bill->update([
            'subtotal' => $subtotal,
            'tax_rate' => $request->tax_rate ?? 0,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'status' => $request->status
        ]);

        return redirect()->route('bills.index')
            ->with('success', 'Bill created successfully.');
    }

    public function show(Bill $bill)
    {
        $bill->load('dealer', 'billItems.product');
        return view('bills.show', compact('bill'));
    }

    public function edit(Bill $bill)
    {
        $bill->load('billItems.product');
        $dealers = Dealer::orderBy('dealer_name')->get();
        $products = Product::orderBy('product_name')->get();

        return view('bills.edit', compact('bill', 'dealers', 'products'));
    }

    public function update(Request $request, Bill $bill)
    {
        $request->validate([
            'dealer_id' => 'required|exists:dealers,id',
            'bill_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000'
        ]);

        $bill->billItems()->delete();

        $subtotal = 0;

        foreach ($request->items as $item) {
            $totalPrice = $item['quantity'] * $item['unit_price'];

            BillItem::create([
                'bill_id' => $bill->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $totalPrice
            ]);

            $subtotal += $totalPrice;
        }

        $taxAmount = ($subtotal * ($request->tax_rate ?? 0)) / 100;
        $totalAmount = $subtotal + $taxAmount;

        $bill->update([
            'dealer_id' => $request->dealer_id,
            'bill_date' => $request->bill_date,
            'subtotal' => $subtotal,
            'tax_rate' => $request->tax_rate ?? 0,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'notes' => $request->notes,
            'is_gst' => ($request->tax_rate ?? 0) > 0
        ]);

        return redirect()->route('bills.index')
            ->with('success', 'Bill updated successfully.');
    }

    public function destroy(Bill $bill)
    {
        try {
            $bill->delete();
            return response()->json(['success' => true, 'message' => 'Bill deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting bill'], 500);
        }
    }

    public function generatePDF(Bill $bill, $type = 'gst')
    {
        $bill->load('dealer', 'billItems.product');

        // Seller custom fields
        $sellerCustomFields = [];
        if ($type === 'gst') {
            $sellerCustomFields['GSTIN'] = config('app.company.gstin', '27ABCDE1234F1Z5');
            $sellerCustomFields['PAN'] = config('app.company.pan', 'ABCDE1234F');
        }

        $client = new Party([
            'name' => config('app.company.name', 'Your Company Name'),
            'phone' => config('app.company.phone', '+91 9876543210'),
            'address' => config('app.company.address', '123 Business Street, City, State'),
            'custom_fields' => $sellerCustomFields,
        ]);

        // Buyer custom fields
        $buyerCustomFields = [];
        if ($type === 'gst') {
            if (filled($bill->dealer->gst)) {
                $buyerCustomFields['GSTIN'] = $bill->dealer->gst;
            }
            if (filled($bill->dealer->pan)) {
                $buyerCustomFields['PAN'] = $bill->dealer->pan;
            }
        }

        $customer = new Party([
            'name' => $bill->dealer->dealer_name,
            'address' => $bill->dealer->address ?? '',
            'phone' => $bill->dealer->mobile_no ?? '',
            'custom_fields' => $buyerCustomFields,
        ]);

        $items = [];
        foreach ($bill->billItems as $item) {
            // Create invoice item WITHOUT tax
            $invoiceItem = InvoiceItem::make($item->product->product_name)
                ->pricePerUnit($item->unit_price)
                ->quantity($item->quantity);

            $items[] = $invoiceItem;
        }

        $invoice = Invoice::make()
            ->series('BILL')
            ->sequence($bill->id)
            ->serialNumberFormat('{SERIES}-{SEQUENCE}')
            ->seller($client)
            ->buyer($customer)
            ->date($bill->bill_date)
            ->dateFormat('d/m/Y')
            ->currencySymbol('₹')
            ->currencyCode('INR')
            ->addItems($items)
            ->notes($bill->notes ?? '')
            ->filename($bill->bill_number . '-' . $type)
            ->template('custom');

        // Use the package's built-in methods for tax calculations
        if ($type === 'gst' && $bill->tax_rate > 0) {
            $invoice->taxRate($bill->tax_rate);
        }

        // Add logo for GST invoices
        if ($type === 'gst') {
            $logoPath = public_path('images/h-logo.png');
            if (file_exists($logoPath)) {
                $invoice->logo($logoPath);
            }
        }

        return $invoice->stream();
    }

    public function updateStatus(Request $request, Bill $bill)
    {
        $request->validate([
            'status' => 'required|in:draft,sent,paid'
        ]);

        $bill->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
            'status' => ucfirst($request->status)
        ]);
    }
}
