@extends('layouts.app')

@section('title', 'Dashboard Super Admin')

@section('content_header')
    <h1>Dashboard Super Admin</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <div class="row">
        <!-- Total Users Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $data['totalUsers'] }}</h3>
                    <p>Total Users</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('users.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <!-- Total Penjualan Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Rp {{ number_format($data['totalPenjualan'], 0, ',', '.') }}</h3>
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

        <!-- Total Stok Ban Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $data['totalStokBan'] }}</h3>
                    <p>Total Stok Ban</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tire"></i>
                </div>
                <a href="{{ route('stokban.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Total Hutang Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>Rp {{ number_format($data['totalSisaHutang'], 0, ',', '.') }}</h3>
                    <p>Sisa Hutang</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <a href="{{ route('hutangretail.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sales Chart -->
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

        <!-- User Statistics -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Statistik User</h3>
                </div>
                <div class="card-body">
                    <canvas id="userChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Transactions -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transaksi Terbaru</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th>Faktur</th>
                                    <th>Pelanggan</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['recentPenjualan'] as $penjualan)
                                <tr>
                                    <td>
                                        <a href="{{ route('penjualan.show', $penjualan->id) }}">
                                            {{ $penjualan->no_faktur }}
                                        </a>
                                    </td>
                                    <td>{{ Str::limit($penjualan->nama_pelanggan, 15) }}</td>
                                    <td>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $penjualan->status == 'Lunas' ? 'success' : ($penjualan->status == 'Pending' ? 'warning' : 'danger') }}">
                                            {{ $penjualan->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada transaksi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Overview -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Overview Sistem</h3>
                </div>
                <div class="card-body">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Aplikasi Berjalan</span>
                            <span class="info-box-number">Normal</span>
                        </div>
                    </div>

                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-database"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Database</span>
                            <span class="info-box-number">Connected</span>
                        </div>
                    </div>

                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-exclamation-triangle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Stok Rendah</span>
                            <span class="info-box-number">{{ $data['lowStockItems'] }}</span>
                        </div>
                    </div>

                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-times-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Hutang Belum Lunas</span>
                            <span class="info-box-number">{{ $data['hutangBelumLunas'] }}</span>
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
        // Data dari PHP - di-pass melalui PHP murni
        var salesLabels = JSON.parse('<?php 
            echo addslashes(json_encode($data['salesChartData']['labels'] ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'])); 
        ?>');
        
        var salesData = JSON.parse('<?php 
            echo addslashes(json_encode($data['salesChartData']['data'] ?? [0, 0, 0, 0, 0, 0, 0])); 
        ?>');
        
        var superadminCount = <?php echo $data['userStats']['superadmin'] ?? 0; ?>;
        var adminCount = <?php echo $data['userStats']['admin'] ?? 0; ?>;
        var userCount = <?php echo $data['userStats']['user'] ?? 0; ?>;

        // Sales Chart
        var salesCanvas = document.getElementById('salesChart');
        if (salesCanvas) {
            var salesCtx = salesCanvas.getContext('2d');
            var salesChart = new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: salesLabels,
                    datasets: [{
                        label: 'Total Penjualan',
                        data: salesData,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
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
                                    if (value === 0) {
                                        return 'Rp 0';
                                    }
                                    if (typeof value === 'number') {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                    return 'Rp 0';
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var value = context.raw;
                                    if (typeof value === 'number') {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                    return 'Rp 0';
                                }
                            }
                        }
                    }
                }
            });
        }

        // User Chart
        var userCanvas = document.getElementById('userChart');
        if (userCanvas) {
            var userCtx = userCanvas.getContext('2d');
            var userChart = new Chart(userCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Super Admin', 'Admin', 'User'],
                    datasets: [{
                        data: [superadminCount, adminCount, userCount],
                        backgroundColor: ['#dc3545', '#ffc107', '#28a745'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    });
</script>
@endpush