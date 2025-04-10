<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Http\Requests\StoreSalesOrderRequest;
use App\Http\Requests\UpdateSalesOrderRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesOrderController extends Controller
{
    public function index()
    {
        $salesOrders = SalesOrder::with('customer')->get();
        return view('sales_orders.index', compact('salesOrders'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('sales_orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'order_date' => 'required|date',
            'items' => 'required|array'
        ]);

        DB::transaction(function () use ($request) {
            $order = SalesOrder::create([
                'customer_id' => $request->customer_id,
                'order_date' => $request->order_date,
                'total' => 0
            ]);

            $total = 0;
            foreach ($request->items as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
                SalesOrderItem::create([
                    'sales_order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal
                ]);
            }

            $order->update(['total' => $total]);
        });

        return redirect()->route('sales-orders.index')->with('success', 'Sales Order berhasil dibuat');
    }

    public function edit(SalesOrder $salesOrder)
    {
        $customers = Customer::all();
        $products = Product::all();
        $salesOrder->load('items.product'); // include item + relasi produk
        return view('sales_orders.edit', compact('salesOrder', 'customers', 'products'));
    }

    public function update(Request $request, SalesOrder $salesOrder)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'items' => 'required|array',
        ]);

        DB::transaction(function () use ($request, $salesOrder) {
            $salesOrder->update([
                'customer_id' => $request->customer_id,
                'order_date' => $request->order_date,
            ]);

            // Hapus semua item lama
            $salesOrder->items()->delete();

            $total = 0;

            // Tambah ulang item baru
            foreach ($request->items as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;

                SalesOrderItem::create([
                    'sales_order_id' => $salesOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);
            }

            $salesOrder->update(['total' => $total]);
        });

        return redirect()->route('sales-orders.index')->with('success', 'Sales Order berhasil diperbarui');
    }
    public function destroy(SalesOrder $salesOrder)
    {
        $salesOrder->delete();
        return redirect()->route('sales-orders.index')->with('success', 'Sales order berhasil dihapus');
    }
}
