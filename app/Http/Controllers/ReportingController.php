<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ReportingController extends Controller
{
    public function index()
    {
        return redirect()->route('products.report');
    }

    public function report(Request $request)
    {
        $query = Product::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->sort == 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort == 'price_desc') {
            $query->orderBy('price', 'desc');
        }

        $products = $query->get();

        $total_products = $products->count();
        $avg_price = $products->avg('price');
        $total_value = $products->sum('price');

        $grouped = $products->groupBy('category')->map(function ($group) {
            return [
                'jumlah_produk' => $group->count(),
                'total_harga' => $group->sum('price'),
                'rata2_harga' => $group->avg('price'),
            ];
        });

        $categories = Product::select('category')->distinct()->pluck('category');
        // Ambil dari hasil filter
        $pivotData = $products->map(function ($item) {
            return [
                'name' => $item->name,
                'category' => $item->category,
                'unit' => $item->unit,
                'price' => $item->price,
            ];
        });

        return view('reporting.index', compact(
            'products',
            'pivotData',
            'total_products',
            'avg_price',
            'total_value',
            'grouped',
            'categories'
        ));
    }
}
