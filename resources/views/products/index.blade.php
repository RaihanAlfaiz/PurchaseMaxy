@extends('layouts.mazer-admin')
@section('heading')
    Dashboard
@endsection

@section('content')
    <div class="container">
        <h1>Daftar Produk</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Tambah Produk</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Satuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category }}</td>
                        <td>{{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>{{ $product->unit }}</td>
                        <td>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</button>
                            </form>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
