<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customers = Customer::select(['id', 'name', 'address', 'gst', 'pan_card', 'phone_no', 'created_at']);

            return DataTables::of($customers)
                ->addIndexColumn()
                ->editColumn('created_at', function ($customer) {
                    return $customer->created_at->format('d/m/Y');
                })
                ->editColumn('address', function ($customer) {
                    return strlen($customer->address) > 50 ? substr($customer->address, 0, 50) . '...' : $customer->address;
                })
                ->addColumn('action', function ($customer) {
                    return '
                        <a href="' . route('customers.show', $customer->id) . '" class="btn btn-info btn-sm">View</a>
                        <a href="' . route('customers.edit', $customer->id) . '" class="btn btn-warning btn-sm">Edit</a>
                        <form action="' . route('customers.destroy', $customer->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('customers.index');
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'gst' => 'nullable|string|max:50',
            'pan_card' => 'nullable|string|max:20',
            'phone_no' => 'required|string|max:15',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'gst' => 'nullable|string|max:50',
            'pan_card' => 'nullable|string|max:20',
            'phone_no' => 'required|string|max:15',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
