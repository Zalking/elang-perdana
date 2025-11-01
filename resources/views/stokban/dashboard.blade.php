@extends('layouts.app')

@section('title', 'Dashboard Stok Ban')

@section('content_header')
    <h1>Dashboard Stok Ban</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Stok Ban</li>
@endsection

@section('content')
    <div class="row">
        <!-- Total Stok Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalStok }}</h3>
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

        <!-- Total Nilai Stok Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Rp {{ number_format($totalNilaiStok, 0, ',', '.') }}</h3>
                    <p>Total Nilai Stok</p>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <a href="{{ route('stokban.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Total Items Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalItems }}</h3>
                    <p>Total Item Ban</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <a href="{{ route('stokban.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Low Stock Alert Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $lowStockItems }}</h3>
                    <p>Stok Rendah</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <a href="{{ route('stokban.index') }}?status=Hampir+Habis" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Brand Statistics -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Statistik Stok per Brand</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Brand</th>
                                    <th>Jumlah Item</th>
                                    <th>Total Stok</th>
                                    <th>Total Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($brandStats as $brand)
                                <tr>
                                    <td><strong>{{ $brand->brand }}</strong></td>
                                    <td>{{ $brand->total_items }}</td>
                                    <td>{{ $brand->total_stok }}</td>
                                    <td>Rp {{ number_format($brand->total_nilai, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada data stok ban</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Peringatan Stok Rendah</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th>Nama Ban</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockAlerts as $ban)
                                <tr>
                                    <td>
                                        <a href="{{ route('stokban.show', $ban->id) }}">
                                            {{ Str::limit($ban->nama_ban, 20) }}
                                        </a>
                                    </td>
                                    <td>{{ $ban->stok }}</td>
                                    <td>
                                        <span class="badge badge-{{ $ban->status == 'Hampir Habis' ? 'warning' : 'danger' }}">
                                            {{ $ban->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada peringatan stok</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Stok -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Stok Ban Terbaru</h3>
                    <div class="card-tools">
                        <a href="{{ route('stokban.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Tambah Stok Ban
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Kode Ban</th>
                                    <th>Nama Ban</th>
                                    <th>Brand</th>
                                    <th>Ukuran</th>
                                    <th>Stok</th>
                                    <th>Harga Beli</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentStok as $ban)
                                <tr>
                                    <td>{{ $ban->kode_ban }}</td>
                                    <td>
                                        <a href="{{ route('stokban.show', $ban->id) }}">
                                            {{ $ban->nama_ban }}
                                        </a>
                                    </td>
                                    <td>{{ $ban->brand }}</td>
                                    <td>{{ $ban->ukuran }}</td>
                                    <td>{{ $ban->stok }}</td>
                                    <td>Rp {{ number_format($ban->harga_beli, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $ban->status == 'Tersedia' ? 'success' : ($ban->status == 'Hampir Habis' ? 'warning' : 'danger') }}">
                                            {{ $ban->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('stokban.edit', $ban->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('stokban.show', $ban->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada data stok ban</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('stokban.index') }}" class="btn btn-sm btn-default">
                        Lihat Semua Stok Ban
                    </a>
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
                            <a href="{{ route('stokban.create') }}" class="btn btn-app bg-success">
                                <i class="fas fa-plus"></i> Tambah Stok
                            </a>
                        </div>
                        <div class="col-lg-3 col-6">
                            <a href="{{ route('stokban.index') }}" class="btn btn-app bg-info">
                                <i class="fas fa-list"></i> Lihat Semua
                            </a>
                        </div>
                        <div class="col-lg-3 col-6">
                            <a href="{{ route('stokban.index') }}?status=Hampir+Habis" class="btn btn-app bg-warning">
                                <i class="fas fa-exclamation-triangle"></i> Stok Rendah
                            </a>
                        </div>
                        <div class="col-lg-3 col-6">
                            <a href="{{ route('hutangretail.create') }}" class="btn btn-app bg-primary">
                                <i class="fas fa-file-invoice-dollar"></i> Hutang Retail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .small-box {
        border-radius: 0.25rem;
        position: relative;
        display: block;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
    }
    .small-box:hover {
        text-decoration: none;
        box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
    }
</style>
@endpush