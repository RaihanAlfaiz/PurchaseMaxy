@extends('layouts.mazer-admin')
@section('heading')
    Dashboard
@endsection

@section('content')
    <div class="container">
        <h1>Edit Supplier</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $supplier->name }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $supplier->email }}">
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">No. Telepon</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ $supplier->phone }}">
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Alamat</label>
                <textarea class="form-control" id="address" name="address">{{ $supplier->address }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
