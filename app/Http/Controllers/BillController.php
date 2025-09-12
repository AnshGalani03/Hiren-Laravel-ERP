<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class BillController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $bills = Bill::query()->with('customer');

            // Filter by customer name
            if ($request->has('customer_name') && !empty($request->customer_name)) {
                $bills->whereHas('customer', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->customer_name . '%');
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

            // ✅ Use DataTables facade with ::of() method instead of ::eloquent()
            return DataTables::of($bills)
                ->addIndexColumn()
                ->editColumn('bill_date', function ($bill) {
                    return $bill->bill_date->format('d/m/Y');
                })
                ->addColumn('customer_name', function ($bill) {
                    return $bill->customer->name ?? 'N/A';
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
                        <div class="btn-group" role="group">
                            <a href="' . route('bills.show', $bill->id) . '" class="btn btn-info btn-sm">View</a>
                            <a href="' . route('bills.edit', $bill->id) . '" class="btn btn-warning btn-sm">Edit</a>
                            <a href="' . route('bills.pdf', ['bill' => $bill->id]) . '" class="btn btn-success btn-sm">PDF</a>
                            <button class="btn btn-danger btn-sm delete-bill" data-id="' . $bill->id . '"  data-bill-number="' . $bill->bill_number . '">Delete</button>
                        </div>
                    ';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        // Load customers for filter dropdown
        $customers = Customer::orderBy('name')->get();
        return view('bills.index', compact('customers'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::all();
        return view('bills.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'bill_date' => 'required|date',
            'is_gst' => 'boolean',
            'tax_rate' => 'required_if:is_gst,1|nullable|numeric|min:0|max:100',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'status' => 'required|in:draft,sent,paid',
            'notes' => 'nullable|string|max:1000'
        ]);

        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $isGst = $request->has('is_gst');
        $taxRate = $isGst ? ($request->tax_rate ?? 0) : 0;
        $taxAmount = ($subtotal * $taxRate) / 100;
        $totalAmount = $subtotal + $taxAmount;

        $bill = Bill::create([
            'customer_id' => $request->customer_id,
            'bill_date' => $request->bill_date,
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'is_gst' => $isGst,
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        foreach ($request->items as $item) {
            $bill->billItems()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price']
            ]);
        }

        return redirect()->route('bills.index')
            ->with('success', 'Bill created successfully.');
    }

    public function show(Bill $bill)
    {
        $bill->load('customer', 'billItems.product');
        return view('bills.show', compact('bill'));
    }

    public function edit(Bill $bill)
    {
        $bill->load('billItems.product');
        $customers = Customer::orderBy('name')->get();
        $products = Product::all();
        return view('bills.edit', compact('bill', 'customers', 'products'));
    }

    public function update(Request $request, Bill $bill)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'bill_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:draft,sent,paid',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Delete old bill items
        $bill->billItems()->delete();

        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $taxRate = $request->tax_rate ?? 0;
        $taxAmount = ($subtotal * $taxRate) / 100;
        $totalAmount = $subtotal + $taxAmount;

        $bill->update([
            'customer_id' => $request->customer_id,
            'bill_date' => $request->bill_date,
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'is_gst' => $taxRate > 0,
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        foreach ($request->items as $item) {
            $bill->billItems()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price']
            ]);
        }

        return redirect()->route('bills.index')
            ->with('success', 'Bill updated successfully.');
    }

    public function destroy(Bill $bill)
    {
        try {
            $billNumber = $bill->bill_number;

            // Delete all bill items first
            $bill->billItems()->delete();

            // Then delete the bill
            $bill->delete();

            return redirect()->route('bills.index')
                ->with('success', 'Bill #' . $billNumber . ' deleted successfully!');
        } catch (\Exception $e) {

            return redirect()->route('bills.index')
                ->with('error', 'Error deleting bill: ' . $e->getMessage());
        }
    }

    public function pdf(Bill $bill, Request $request)
    {
        $bill->load('customer', 'billItems.product');
        $type = $request->type ?? 'gst';

        $pdf = PDF::loadView('bills.pdf', compact('bill', 'type'));
        return $pdf->download('bill-' . $bill->bill_number . '.pdf');
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

    public function generatePDF(Bill $bill, Request $request)
    {
        // Load customer and billItems relationships
        $bill->load('customer', 'billItems.product');



        // Check if bill is GST or Non-GST based on database field
        $isGstBill = $bill->is_gst;

        // Seller custom fields - only for GST bills
        $sellerCustomFields = [];
        if ($isGstBill) {
            $sellerCustomFields['GSTIN'] = config('app.company.gstin', '27ABCDE1234F1Z5');
            $sellerCustomFields['PAN'] = config('app.company.pan', 'ABCDE1234F');
        }

        $client = new Party([
            'name' => config('app.company.name', 'Your Company Name'),
            'phone' => config('app.company.phone', '+91 9876543210'),
            'address' => config('app.company.address', '123 Business Street, City, State'),
            'custom_fields' => $sellerCustomFields,
        ]);

        // Buyer custom fields - only for GST bills
        $buyerCustomFields = [];
        if ($isGstBill) {
            if (!empty($bill->customer->gst)) {
                $buyerCustomFields['GSTIN'] = $bill->customer->gst;
            }
        }

        $customer = new Party([
            'name' => $bill->customer->name,
            'address' => $bill->customer->address ?? '',
            'phone' => $bill->customer->phone_no ?? '',
            'custom_fields' => $buyerCustomFields,
        ]);

        $items = [];
        foreach ($bill->billItems as $item) {
            // Include HSN code in the product title or description
            $productTitle = $item->product->product_name;
            $productDescription = '';

            if ($item->product->hsn_code) {
                $productDescription = 'HSN: ' . $item->product->hsn_code;
            }

            $invoiceItem = InvoiceItem::make($productTitle)
                ->pricePerUnit($item->unit_price)
                ->quantity($item->quantity)
                ->description($productDescription); // Add HSN in description

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
            ->filename($bill->bill_number . '-' . ($isGstBill ? 'gst' : 'non-gst'))
            ->template('custom');

        // Add tax rate only for GST bills
        if ($isGstBill && $bill->tax_rate > 0) {
            $invoice->taxRate($bill->tax_rate);
        }

        // Pass the original bill data to the view
        view()->share('originalBill', $bill);
        return $invoice->stream();
    }
}
