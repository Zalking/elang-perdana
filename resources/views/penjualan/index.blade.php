@extends('layouts.app')

@section('title', 'Data Penjualan')

@section('content_header')
    <h1>Data Penjualan</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Penjualan</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="card-title">Daftar Penjualan</h3>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('penjualan.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Penjualan
                    </a>
                    <a href="{{ route('penjualan.dashboard') }}" class="btn btn-info">
                        <i class="fas fa-chart-bar"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('penjualan.index') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Cari no faktur, pelanggan, barang..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="metode_pembayaran" class="form-control">
                            <option value="">Semua Metode</option>
                            <option value="Tunai" {{ request('metode_pembayaran') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                            <option value="Transfer" {{ request('metode_pembayaran') == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="Kredit" {{ request('metode_pembayaran') == 'Kredit' ? 'selected' : '' }}>Kredit</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" placeholder="Dari Tanggal">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" placeholder="Sampai Tanggal">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <a href="{{ route('penjualan.index') }}" class="btn btn-secondary btn-sm">Reset Filter</a>
                    </div>
                </div>
            </form>

            <!-- Statistics Cards -->
            <div class="row mb-3">
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h4>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h4>
                            <p>Total Penjualan</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h4>{{ $totalTransaksi }}</h4>
                            <p>Total Transaksi</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h4>Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</h4>
                            <p>Penjualan Hari Ini</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No Faktur</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Total</th>
                            <th>Metode Bayar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualans as $penjualan)
                        <tr>
                            <td>{{ $penjualan->no_faktur }}</td>
                            <td>{{ $penjualan->tanggal_penjualan->format('d/m/Y') }}</td>
                            <td>{{ $penjualan->nama_pelanggan }}</td>
                            <td>{{ $penjualan->nama_barang }}</td>
                            <td>{{ $penjualan->jumlah }}</td>
                            <td>Rp {{ number_format($penjualan->harga_satuan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-{{ $penjualan->metode_pembayaran == 'Tunai' ? 'success' : ($penjualan->metode_pembayaran == 'Transfer' ? 'primary' : 'warning') }}">
                                    {{ $penjualan->metode_pembayaran }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $penjualan->status == 'Lunas' ? 'success' : ($penjualan->status == 'Pending' ? 'warning' : 'danger') }}">
                                    {{ $penjualan->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('penjualan.show', $penjualan->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('penjualan.edit', $penjualan->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('penjualan.destroy', $penjualan->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data penjualan?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada data penjualan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $penjualans->links() }}
            </div>
        </div>
    </div>
@endsection