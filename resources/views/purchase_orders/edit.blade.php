@extends('layouts.mazer-admin')

@section('heading')
    Dashboard
@endsection

@section('content')
    <div class="container">
        <h1>Edit Purchase Order</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('purchase-orders.update', $purchaseOrder->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="supplier_id" class="form-label">Supplier</label>
                <select name="supplier_id" id="supplier_id" class="form-control" required>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}"
                            {{ $purchaseOrder->supplier_id == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="order_date" class="form-label">Tanggal Order</label>
                <input type="date" class="form-control" id="order_date" name="order_date"
                    value="{{ \Carbon\Carbon::parse($purchaseOrder->order_date)->format('Y-m-d') }}" required>
            </div>

            <hr>
            <h4>Daftar Produk</h4>
            <div id="items-container">
                @foreach ($purchaseOrder->items as $index => $item)
                    <div class="item-row row mb-2">
                        <div class="col-md-4">
                            <select name="items[{{ $index }}][product_id]" class="form-control" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ $product->id == $item->product_id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="items[{{ $index }}][quantity]" class="form-control"
                                value="{{ $item->quantity }}" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="items[{{ $index }}][price]" class="form-control"
                                value="{{ $item->price }}" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control subtotal" value="{{ $item->subtotal }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-item">Hapus</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-primary mb-3" id="add-item">Tambah Produk</button>

            <div class="mb-3">
                <label for="grand-total" class="form-label">Total</label>
                <input type="text" class="form-control" id="grand-total" readonly>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        let itemIndex = {{ $purchaseOrder->items->count() }};

        function updateSubtotals() {
            let total = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('[name*="[quantity]"]').value) || 0;
                const price = parseFloat(row.querySelector('[name*="[price]"]').value) || 0;
                const subtotal = qty * price;
                row.querySelector('.subtotal').value = subtotal.toFixed(2);
                total += subtotal;
            });
            document.getElementById('grand-total').value = total.toFixed(2);
        }

        document.getElementById('add-item').addEventListener('click', () => {
            const container = document.getElementById('items-container');
            const row = document.createElement('div');
            row.classList.add('item-row', 'row', 'mb-2');
            row.innerHTML = `
            <div class="col-md-4">
                <select name="items[${itemIndex}][product_id]" class="form-control" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${itemIndex}][quantity]" class="form-control" placeholder="Qty" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${itemIndex}][price]" class="form-control" placeholder="Harga" required>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control subtotal" placeholder="Subtotal" readonly>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-item">Hapus</button>
            </div>
        `;
            container.appendChild(row);
            itemIndex++;
        });

        document.getElementById('items-container').addEventListener('input', e => {
            if (e.target.matches('[name*="[quantity]"], [name*="[price]"]')) {
                updateSubtotals();
            }
        });

        document.getElementById('items-container').addEventListener('click', e => {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('.item-row').remove();
                updateSubtotals();
            }
        });

        // Kalkulasi awal
        updateSubtotals();
    </script>
@endsection
