@extends('layouts.mazer-admin')

@section('heading')
    Edit Sales Order
@endsection

@section('content')
    <div class="container">
        <h1>Edit Sales Order</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('sales-orders.update', $salesOrder->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="customer_id" class="form-label">Pelanggan</label>
                <select name="customer_id" id="customer_id" class="form-control" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}"
                            {{ $salesOrder->customer_id == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="order_date" class="form-label">Tanggal Order</label>
                <input type="date" class="form-control" id="order_date" name="order_date"
                    value="{{ \Carbon\Carbon::parse($salesOrder->order_date)->format('Y-m-d') }}" required>

            </div>

            <hr>
            <h4>Items</h4>
            <div id="items-container">
                @foreach ($salesOrder->items as $index => $item)
                    <div class="row mb-3 item-row">
                        <div class="col-md-4">
                            <select name="items[{{ $index }}][product_id]" class="form-control" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ $product->id == $item->product_id ? 'selected' : '' }}>
                                        {{ $product->name }} (Rp{{ number_format($product->price, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="items[{{ $index }}][quantity]" class="form-control"
                                value="{{ $item->quantity }}" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="items[{{ $index }}][price]" class="form-control"
                                value="{{ $item->price }}" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-item">Hapus</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-info mb-3" id="add-item-btn">Tambah Item</button>

            <br>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('sales-orders.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        let itemIndex = {{ $salesOrder->items->count() }};

        document.getElementById('add-item-btn').addEventListener('click', function() {
            const container = document.getElementById('items-container');

            const itemRow = document.createElement('div');
            itemRow.classList.add('row', 'mb-3', 'item-row');
            itemRow.innerHTML = `
                <div class="col-md-4">
                    <select name="items[${itemIndex}][product_id]" class="form-control" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (Rp{{ number_format($product->price, 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="items[${itemIndex}][quantity]" class="form-control" placeholder="Qty" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="items[${itemIndex}][price]" class="form-control" placeholder="Harga" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-item">Hapus</button>
                </div>
            `;
            container.appendChild(itemRow);
            itemIndex++;
        });

        // remove row
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-item')) {
                e.target.closest('.item-row').remove();
            }
        });
    </script>
@endsection
