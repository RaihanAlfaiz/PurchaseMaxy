@extends('layouts.mazer-admin')

@section('heading')
    Dashboard
@endsection

@section('content')
    <div class="container">
        <h1>Buat Sales Order</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('sales-orders.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="customer_id" class="form-label">Pelanggan</label>
                <select name="customer_id" id="customer_id" class="form-control" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="order_date" class="form-label">Tanggal Order</label>
                <input type="date" class="form-control" id="order_date" name="order_date" required>
            </div>

            <hr>

            <h4>Items</h4>
            <div id="items-container">
                <div class="row mb-3 item-row">
                    <div class="col-md-4">
                        <select name="items[0][product_id]" class="form-control" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}
                                    (Rp{{ number_format($product->price, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="items[0][quantity]" class="form-control" placeholder="Qty"
                            min="1" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="items[0][price]" class="form-control" placeholder="Harga" min="0"
                            required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-item">Hapus</button>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-info mb-3" id="add-item-btn">Tambah Item</button>

            <br>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('sales-orders.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        let itemIndex = 1;

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
                    <input type="number" name="items[${itemIndex}][quantity]" class="form-control" placeholder="Qty" min="1" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="items[${itemIndex}][price]" class="form-control" placeholder="Harga" min="0" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-item">Hapus</button>
                </div>
            `;

            container.appendChild(itemRow);
            itemIndex++;
        });

        // Hapus baris item
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-item')) {
                const row = e.target.closest('.item-row');
                row.remove();
            }
        });
    </script>
@endsection
