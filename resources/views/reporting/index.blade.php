@extends('layouts.mazer-admin')

@section('title', 'Laporan Produk')

@section('heading')
    Reporting Produk
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/pivot.min.css" />
    <style>
        .pvtUi {
            background: #fff;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }

        .pvtRenderer,
        .pvtAggregator,
        .pvtAttrDropdown,
        .pvtAxisContainer,
        .pvtVals {
            margin: 4px;
            font-size: 14px;
        }

        .pvtTable {
            font-size: 14px;
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .pvtTable th,
        .pvtTable td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: right;
        }

        .pvtTable th {
            background: #f5f5f5;
            font-weight: 600;
            color: #333;
        }

        .pvtTable td:first-child,
        .pvtTable th:first-child {
            text-align: left;
        }

        .select2-container--default .select2-selection--single {
            height: calc(2.5rem + 2px);
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            font-size: 1rem;
            background-color: #fff;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.8rem;
            color: #495057;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            right: 10px;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>
@endpush


@push('scripts')
    {{-- jQuery wajib di atas semua --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- PIVOT: Tambahan dependensi jQuery UI --}}
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/pivot.min.js"></script>

    {{-- Select2 & Chart --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(function() {
            $('.select2').select2();

            // CHART
            const ctx = document.getElementById('productChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($grouped->keys()) !!},
                    datasets: [{
                        label: 'Total Harga per Kategori',
                        data: {!! json_encode($grouped->pluck('total_harga')) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(255, 159, 64, 0.7)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });

            // PIVOT TABLE DENGAN CUSTOM AGGREGATOR YANG VALID
            const customAggregators = {
                "Total Harga (Sum)": function() {
                    return $.pivotUtilities.aggregatorTemplates.sum()(["price"]);
                },
                "Rata-rata Harga (Avg)": function() {
                    return $.pivotUtilities.aggregatorTemplates.average()(["price"]);
                }
            };

            if ($.fn.pivotUI) {
                $("#pivot-table").pivotUI(
                    {!! $pivotData->toJson() !!}, {
                        rows: ["category"],
                        cols: ["unit"],
                        aggregators: customAggregators,
                        vals: ["price"],
                        aggregatorName: "Total Harga (Sum)",
                        rendererName: "Table"
                    }
                );
            } else {
                console.error('pivotUI not found.');
            }
        });
    </script>
@endpush


@section('content')
    <div class="container">
        <form method="GET" action="{{ route('products.report') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="name" class="form-control" placeholder="Cari nama produk"
                    value="{{ request('name') }}">
            </div>
            <div class="col-md-4">
                <select name="category" class="form-select select2">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                            {{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="sort" class="form-select">
                    <option value="">Urutkan</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Termurah</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Termahal
                    </option>
                </select>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary w-100">Terapkan Filter</button>
            </div>
        </form>

        <h5>Executive Summary</h5>
        <ul>
            <li>Total Produk: <strong>{{ $total_products }}</strong></li>
            <li>Rata-rata Harga: <strong>Rp{{ number_format($avg_price, 2) }}</strong></li>
            <li>Total Nilai Produk: <strong>Rp{{ number_format($total_value, 2) }}</strong></li>
        </ul>

        <hr>

        <h5>Data Produk</h5>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Satuan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category }}</td>
                        <td>Rp{{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->unit }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <hr>

        <h5>Chart Statistik</h5>
        <canvas id="productChart" height="180" style="max-width: 400px; margin: auto;"></canvas>



        <hr>

        <h5>Pivot Table (What-if Analysis)</h5>
        <div class="card p-3">
            <div id="pivot-table" style="overflow-x:auto;"></div>
        </div>

    </div>
@endsection
