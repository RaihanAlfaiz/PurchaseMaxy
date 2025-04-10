@extends('layouts.mazer-admin')
@section('heading')
    Dashboard
@endsection

@section('content')
    <div class="container">
        <h1>Daftar Supplier</h1>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary mb-3">Tambah Supplier</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $supplier)
                    <tr>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->email }}</td>
                        <td>{{ $supplier->phone }}</td>
                        <td>{{ $supplier->address }}</td>
                        <td>
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus supplier ini?')">Hapus</button>
                            </form>
                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
