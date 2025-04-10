@extends('layouts.mazer-admin')

@section('heading')
    Sales Orders
@endsection

@section('content')
    <div class="container">
        <h1>Daftar Sales Order</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('sales-orders.create') }}" class="btn btn-primary mb-3">+ Buat Sales Order</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Tanggal Order</th>
                    <th>Total (Rp)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($salesOrders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->customer->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y') }}</td>
                        <td>{{ number_format($order->total, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('sales-orders.edit', $order->id) }}" class="btn btn-sm btn-warning">Edit</a>


                            <form action="{{ route('sales-orders.destroy', $order->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada Sales Order.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
