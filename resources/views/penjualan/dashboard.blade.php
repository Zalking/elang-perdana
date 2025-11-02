@extends('layouts.app')

@section('title', 'Dashboard Penjualan Ban')

@section('content_header')
    <h1>Dashboard Penjualan Ban</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Penjualan</li>
@endsection

@section('content')
    <div class="row">
        <!-- Total YTD Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($totalYTD, 0, ',', '.') }}</h3>
                    <p>Total YTD</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <a href="{{ route('penjualan.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Total MTD Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($totalMTD, 0, ',', '.') }}</h3>
                    <p>Total MTD</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="{{ route('penjualan.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Total Records Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($totalRecords, 0, ',', '.') }}</h3>
                    <p>Total Records</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <a href="{{ route('penjualan.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Export vs Domestic Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($totalExport + $totalDomestic, 0, ',', '.') }}</h3>
                    <p>MTD Export/Domestic</p>
                    <small>Export: {{ number_format($totalExport, 0, ',', '.') }} | Domestic: {{ number_format($totalDomestic, 0, ',', '.') }}</small>
                </div>
                <div class="icon">
                    <i class="fas fa-globe"></i>
                </div>
                <a href="{{ route('penjualan.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Monthly Sales Chart -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Grafik Penjualan Bulanan</h3>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Brands -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top 5 Brands (YTD)</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th>Brand</th>
                                    <th>YTD</th>
                                    <th>MTD</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topBrands as $brand)
                                <tr>
                                    <td>{{ $brand->brand }}</td>
                                    <td>{{ number_format($brand->total_ytd, 0, ',', '.') }}</td>
                                    <td>{{ number_format($brand->total_mtd, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Data -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Data Terbaru</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>YTD</th>
                                    <th>MTD</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPenjualan as $penjualan)
                                <tr>
                                    <td>
                                        <a href="{{ route('penjualan.show', $penjualan->id) }}">
                                            {{ \Str::limit($penjualan->customer, 20) }}
                                        </a>
                                        <br>
                                        <small class="text-muted">{{ $penjualan->brand }}</small>
                                    </td>
                                    <td>{{ number_format($penjualan->ytd, 0, ',', '.') }}</td>
                                    <td>{{ number_format($penjualan->mtd, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Section -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Import Data Excel</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('penjualan.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file">Pilih File Excel</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                            <small class="form-text text-muted">
                                Format file harus sesuai dengan template Excel penjualan ban
                            </small>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Import Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('monthlyChart');
        if (ctx) {
            ctx = ctx.getContext('2d');
            
            var monthlyData = <?php echo json_encode($monthlyData); ?>;
            var labels = Object.keys(monthlyData);
            var data = Object.values(monthlyData);

            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Penjualan Bulanan',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Penjualan: ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush