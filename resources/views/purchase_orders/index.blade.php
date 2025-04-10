@extends('layouts.mazer-admin')
@section('heading')
    Dashboard
@endsection

@section('content')
    <div class="container">
        <h1>Riwayat Pembelian</h1>
        <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary mb-3">Buat Purchase Order</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseOrders as $order)
                    <tr>
                        <td>{{ $order->order_date }}</td>
                        <td>{{ $order->supplier->name }}</td>
                        <td>{{ number_format($order->total, 0, ',', '.') }}</td>
                        <td>

                            <a href="{{ route('purchase-orders.edit', $order->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('purchase-orders.destroy', $order->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
