<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::query();

            return DataTables::eloquent($products)
                ->addIndexColumn()
                ->editColumn('date', function ($product) {
                    return $product->date->format('d/m/Y');
                })
                ->editColumn('hsn_code', function ($product) {
                    return $product->hsn_code ?? 'N/A';
                })
                ->addColumn('action', function ($product) {
                    return '
                        <a href="' . route('products.show', $product->id) . '" class="btn btn-info btn-sm">View</a>
                        <a href="' . route('products.edit', $product->id) . '" class="btn btn-warning btn-sm">Edit</a>
                        <form action="' . route('products.destroy', $product->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('products.index');
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255|unique:products,product_name',
            'hsn_code' => 'nullable|string|max:20',
        ]);

        Product::create([
            'product_name' => $request->product_name,
            'hsn_code' => $request->hsn_code,
            'date' => Carbon::now()->toDateString()
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name' => 'required|string|max:255|unique:products,product_name,' . $product->id,
            'hsn_code' => 'nullable|string|max:20',
        ]);

        $product->update([
            'product_name' => $request->product_name,
            'hsn_code' => $request->hsn_code,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
