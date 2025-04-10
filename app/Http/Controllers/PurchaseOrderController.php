<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('supplier')->get();
        return view('purchase_orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchase_orders.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'order_date' => 'required|date',
            'items' => 'required|array'
        ]);

        DB::transaction(function () use ($request) {
            $order = PurchaseOrder::create([
                'supplier_id' => $request->supplier_id,
                'order_date' => $request->order_date,
                'total' => 0
            ]);

            $total = 0;
            foreach ($request->items as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
                PurchaseOrderItem::create([
                    'purchase_order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal
                ]);
            }

            $order->update(['total' => $total]);
        });

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dibuat');
    }


    public function edit(PurchaseOrder $purchaseOrder)
    {
        $suppliers = Supplier::all();
        $products = Product::all(); // Menambahkan produk untuk dropdown
        return view('purchase_orders.edit', compact('purchaseOrder', 'suppliers', 'products')); // Mengirimkan data
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        // Validasi data yang diterima
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $purchaseOrder) {
            // Update data PurchaseOrder
            $purchaseOrder->update([
                'supplier_id' => $validated['supplier_id'],
                'order_date' => $validated['order_date'],
            ]);

            // Menghapus item lama sebelum menambahkan item baru
            $purchaseOrder->items()->delete();

            $total = 0;
            // Menambahkan item-item baru
            foreach ($validated['items'] as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;

                // Menambahkan item ke PurchaseOrderItem
                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);
            }

            // Update total pada PurchaseOrder
            $purchaseOrder->update(['total' => $total]);
        });

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil diperbarui');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();
        return redirect()->route('purchase-orders.index')->with('success', 'Purchase order berhasil dihapus');
    }
}
