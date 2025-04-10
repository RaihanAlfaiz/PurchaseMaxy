@extends('layouts.mazer-admin')
@section('heading')
    Dashboard
@endsection

@section('content')
    <div class="container">
        <h1>Edit Produk</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Harga</label>
                <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Kategori</label>
                <input type="text" class="form-control" id="category" name="category" value="{{ $product->category }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="unit" class="form-label">Unit</label>
                <input type="number" class="form-control" id="unit" name="unit" value="{{ $product->unit }}"
                    required>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
