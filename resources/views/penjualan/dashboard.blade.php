@extends('layouts.app')

@section('title', 'Dashboard Penjualan')

@section('content_header')
    <h1>Dashboard Penjualan</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Penjualan</li>
@endsection

@section('content')
    <div class="row">
        <!-- Total Penjualan Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
                    <p>Total Penjualan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <a href="{{ route('penjualan.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Total Transaksi Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalTransaksi }}</h3>
                    <p>Total Transaksi</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="{{ route('penjualan.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Total Quantity Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($totalQuantity, 0, ',', '.') }} pcs</h3>
                    <p>Total Barang Terjual</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <a href="{{ route('penjualan.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Rata-rata Penjualan Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>Rp {{ number_format($rataRataPenjualan, 0, ',', '.') }}</h3>
                    <p>Rata-rata Penjualan/Bulan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calculator"></i>
                </div>
                <a href="{{ route('penjualan.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart Section -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Grafik Penjualan 7 Hari Terakhir</h3>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Penjualan -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transaksi Terbaru</h3>
                    <div class="card-tools">
                        <a href="{{ route('penjualan.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Tambah
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th>Faktur</th>
                                    <th>Total</th>
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPenjualan as $penjualan)
                                <tr>
                                    <td>
                                        <a href="{{ route('penjualan.show', $penjualan->id) }}">
                                            {{ $penjualan->no_faktur }}
                                        </a>
                                        <br>
                                        <small class="text-muted">{{ $penjualan->nama_pelanggan }}</small>
                                    </td>
                                    <td>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                                    <td>{{ number_format($penjualan->jumlah, 0, ',', '.') }} pcs</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada transaksi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aksi Cepat</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <a href="{{ route('penjualan.create') }}" class="btn btn-app bg-success">
                                <i class="fas fa-plus"></i> Tambah Penjualan
                            </a>
                        </div>
                        <div class="col-lg-3 col-6">
                            <a href="{{ route('penjualan.index') }}" class="btn btn-app bg-info">
                                <i class="fas fa-list"></i> Lihat Semua
                            </a>
                        </div>
                        <div class="col-lg-3 col-6">
                            <a href="{{ route('penjualan.index') }}?status=Lunas" class="btn btn-app bg-warning">
                                <i class="fas fa-check"></i> Status Lunas
                            </a>
                        </div>
                        <div class="col-lg-3 col-6">
                            <a href="{{ route('penjualan.index') }}?metode_pembayaran=Tunai" class="btn btn-app bg-primary">
                                <i class="fas fa-money-bill"></i> Pembayaran Tunai
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('salesChart');
        if (ctx) {
            ctx = ctx.getContext('2d');
            
            var chartLabels = <?php echo json_encode($chartData['labels'] ?? []); ?>;
            var quantityData = <?php echo json_encode($chartData['quantity'] ?? []); ?>;
            var totalData = <?php echo json_encode($chartData['total'] ?? []); ?>;

            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [
                        {
                            label: 'Penjualan (pcs)',
                            data: quantityData,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Total Penjualan (Rp)',
                            data: totalData,
                            type: 'line',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: false,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Quantity (pcs)'
                            },
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('id-ID') + ' pcs';
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Total (Rp)'
                            },
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false,
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.datasetIndex === 0) {
                                        // Untuk quantity (pcs)
                                        label += context.parsed.y.toLocaleString('id-ID') + ' pcs';
                                    } else {
                                        // Untuk total (Rp)
                                        label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                    return label;
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