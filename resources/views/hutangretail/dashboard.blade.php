@extends('layouts.app')

@section('title', 'Dashboard Hutang Retail')

@section('content_header')
    <h1>Dashboard Hutang Retail</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Hutang Retail</li>
@endsection

@section('content')
    <div class="row">
        <!-- Total Hutang Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($totalHutang, 0, ',', '.') }} Ban</h3>
                    <p>Total Hutang (Ban)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <a href="{{ route('hutangretail.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Total Dibayar Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($totalDibayar, 0, ',', '.') }} Ban</h3>
                    <p>Total Dibayar (Ban)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <a href="{{ route('hutangretail.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Sisa Hutang Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($totalSisaHutang, 0, ',', '.') }} Ban</h3>
                    <p>Sisa Hutang (Ban)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <a href="{{ route('hutangretail.index') }}?status=Belum+Lunas" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Hutang Belum Lunas Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $hutangBelumLunas }}</h3>
                    <p>Hutang Belum Lunas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <a href="{{ route('hutangretail.index') }}?status=Belum+Lunas" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Hutang -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hutang Terbaru</h3>
                    <div class="card-tools">
                        <a href="{{ route('hutangretail.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Tambah Hutang
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No Invoice</th>
                                    <th>Retail</th>
                                    <th>Ban</th>
                                    <th>Total Hutang</th>
                                    <th>Dibayar</th>
                                    <th>Sisa</th>
                                    <th>Status</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentHutang as $hutang)
                                <tr>
                                    <td>
                                        <strong>{{ $hutang->no_invoice }}</strong>
                                    </td>
                                    <td>{{ $hutang->nama_retail }}</td>
                                    <td>
                                        @if($hutang->stokBan)
                                            {{ $hutang->stokBan->nama_ban }}
                                        @else
                                            <span class="text-danger">Ban tidak ditemukan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $hutang->jumlah_ban }} Ban</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $hutang->dibayar }} Ban</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $hutang->sisa_hutang }} Ban</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $hutang->status == 'Lunas' ? 'success' : ($hutang->status == 'Belum Lunas' ? 'warning' : 'danger') }}">
                                            {{ $hutang->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($hutang->tanggal_jatuh_tempo)
                                            {{ $hutang->tanggal_jatuh_tempo->format('d/m/Y') }}
                                            @if($hutang->isTerlambat())
                                                <br><small class="text-danger">+{{ $hutang->hari_keterlambatan }} hari</small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('hutangretail.show', $hutang->id) }}" class="btn btn-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('hutangretail.edit', $hutang->id) }}" class="btn btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(!$hutang->isLunas())
                                                <button type="button" class="btn btn-success" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#bayarModal{{ $hutang->id }}"
                                                        title="Bayar Hutang">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </button>
                                            @endif
                                        </div>

                                        <!-- Bayar Modal -->
                                        <div class="modal fade" id="bayarModal{{ $hutang->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('hutangretail.bayar', $hutang->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Bayar Hutang</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p><strong>No Invoice:</strong> {{ $hutang->no_invoice }}</p>
                                                            <p><strong>Nama Retail:</strong> {{ $hutang->nama_retail }}</p>
                                                            <p><strong>Sisa Hutang:</strong> {{ $hutang->sisa_hutang }} Ban</p>
                                                            
                                                            <div class="form-group">
                                                                <label for="jumlah_bayar{{ $hutang->id }}">Jumlah Ban yang Dikembalikan:</label>
                                                                <input type="number" class="form-control" 
                                                                       id="jumlah_bayar{{ $hutang->id }}" 
                                                                       name="jumlah_bayar" 
                                                                       min="1" 
                                                                       max="{{ $hutang->sisa_hutang }}"
                                                                       required>
                                                                <small class="form-text text-muted">
                                                                    Maksimal: {{ $hutang->sisa_hutang }} ban
                                                                </small>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                                        <p class="text-muted">Tidak ada data hutang retail</p>
                                        <a href="{{ route('hutangretail.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>Tambah Hutang Pertama
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Overview & Top Retail -->
        <div class="col-md-4">
            <!-- Status Overview -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Status Hutang</h3>
                </div>
                <div class="card-body">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Lunas</span>
                            <span class="info-box-number">
                                {{ $hutangByStatus['Lunas'] ?? 0 }}
                            </span>
                        </div>
                    </div>

                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Belum Lunas</span>
                            <span class="info-box-number">
                                {{ $hutangByStatus['Belum Lunas'] ?? 0 }}
                            </span>
                        </div>
                    </div>

                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-exclamation-triangle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Terlambat</span>
                            <span class="info-box-number">
                                {{ $hutangByStatus['Terlambat'] ?? 0 }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Retail dengan Hutang -->
            @if(isset($topRetail) && $topRetail->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Top Retail dengan Hutang</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Retail</th>
                                    <th>Sisa Hutang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topRetail as $retail)
                                <tr>
                                    <td>{{ $retail->nama_retail }}</td>
                                    <td>
                                        <span class="badge bg-warning">{{ $retail->total_sisa_hutang }} Ban</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aksi Cepat</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <a href="{{ route('hutangretail.create') }}" class="btn btn-app bg-success">
                                <i class="fas fa-plus"></i> Tambah Hutang
                            </a>
                        </div>
                        <div class="col-lg-3 col-6">
                            <a href="{{ route('hutangretail.index') }}" class="btn btn-app bg-info">
                                <i class="fas fa-list"></i> Lihat Semua
                            </a>
                        </div>
                        <div class="col-lg-3 col-6">
                            <a href="{{ route('hutangretail.index') }}?status=Belum+Lunas" class="btn btn-app bg-warning">
                                <i class="fas fa-clock"></i> Belum Lunas
                            </a>
                        </div>
                        <div class="col-lg-3 col-6">
                            <a href="{{ route('hutangretail.index') }}?status=Terlambat" class="btn btn-app bg-danger">
                                <i class="fas fa-exclamation-triangle"></i> Terlambat
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
    $(document).ready(function() {
        // Auto close alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
@endpush