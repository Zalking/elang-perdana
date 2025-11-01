@extends('layouts.app')

@section('title', 'Data Hutang Retail')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice-dollar"></i> Data Hutang Retail
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('hutangretail.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Hutang
                        </a>
                        <a href="{{ route('hutangretail.dashboard') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form action="{{ route('hutangretail.index') }}" method="GET" class="form-inline">
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <input type="text" name="search" class="form-control" 
                                               placeholder="Cari no invoice/nama retail/ban..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <select name="status" class="form-control">
                                            <option value="">Semua Status</option>
                                            <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                                            <option value="Belum Lunas" {{ request('status') == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                            <option value="Terlambat" {{ request('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <select name="retail" class="form-control">
                                            <option value="">Semua Retail</option>
                                            @foreach($retails as $retail)
                                                <option value="{{ $retail }}" {{ request('retail') == $retail ? 'selected' : '' }}>
                                                    {{ $retail }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Filter
                                        </button>
                                        <a href="{{ route('hutangretail.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-refresh"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ number_format($totalHutang) }}</h3>
                                    <p>Total Ban Dipinjam</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-tire"></i>
                                </div>
                                <a href="{{ route('hutangretail.index') }}" class="small-box-footer">
                                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ number_format($totalDibayar) }}</h3>
                                    <p>Total Ban Dikembalikan</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <a href="{{ route('hutangretail.index') }}" class="small-box-footer">
                                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ number_format($totalSisaHutang) }}</h3>
                                    <p>Sisa Ban Dipinjam</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <a href="{{ route('hutangretail.index') }}?status=Belum+Lunas" class="small-box-footer">
                                    Lihat Belum Lunas <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
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
                                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>No Invoice</th>
                                    <th>Tanggal</th>
                                    <th>Nama Retail</th>
                                    <th>Ban</th>
                                    <th>Total Dipinjam</th>
                                    <th>Dikembalikan</th>
                                    <th>Sisa</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hutangRetails as $hutang)
                                <tr class="{{ $hutang->isTerlambat() ? 'table-danger' : '' }}">
                                    <td>{{ $loop->iteration + ($hutangRetails->currentPage() - 1) * $hutangRetails->perPage() }}</td>
                                    <td>
                                        <strong class="text-primary">{{ $hutang->no_invoice }}</strong>
                                    </td>
                                    <td>{{ $hutang->tanggal_hutang->format('d/m/Y') }}</td>
                                    <td>
                                        <strong>{{ $hutang->nama_retail }}</strong>
                                        @if($hutang->kontak_retail)
                                            <br><small class="text-muted">{{ $hutang->kontak_retail }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hutang->stokBan)
                                            <div><strong>{{ $hutang->stokBan->nama_ban }}</strong></div>
                                            <small class="text-muted">
                                                {{ $hutang->stokBan->brand }} 
                                                @if($hutang->stokBan->ukuran)
                                                    - {{ $hutang->stokBan->ukuran }}
                                                @endif
                                            </small>
                                        @else
                                            <span class="text-danger">Ban tidak ditemukan</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $hutang->jumlah_ban }} Ban</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $hutang->dibayar }} Ban</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $hutang->sisa_hutang > 0 ? 'warning' : 'success' }}">
                                            {{ $hutang->sisa_hutang }} Ban
                                        </span>
                                    </td>
                                    <td>
                                        @if($hutang->tanggal_jatuh_tempo)
                                            {{ $hutang->tanggal_jatuh_tempo->format('d/m/Y') }}
                                            @if($hutang->isTerlambat())
                                                <br><small class="text-danger">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    Terlambat {{ $hutang->hari_keterlambatan }} hari
                                                </small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hutang->status == 'Lunas')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> LUNAS
                                            </span>
                                        @elseif($hutang->status == 'Belum Lunas')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock"></i> BELUM LUNAS
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-exclamation-triangle"></i> TERLAMBAT
                                            </span>
                                        @endif
                                        <br>
                                        <small class="text-muted">
                                            {{ number_format($hutang->progress, 1) }}%
                                        </small>
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
                                    <td colspan="11" class="text-center py-4">
                                        <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Tidak ada data hutang retail ditemukan</h5>
                                        <p class="text-muted">Silakan tambah data hutang retail baru</p>
                                        <a href="{{ route('hutangretail.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>Tambah Hutang Retail
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Menampilkan {{ $hutangRetails->firstItem() ?? 0 }} - {{ $hutangRetails->lastItem() ?? 0 }} dari {{ $hutangRetails->total() }} data
                        </div>
                        <div>
                            {{ $hutangRetails->links() }}
                        </div>
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
            $('.alert').alert('close');
        }, 5000);

        // Auto submit form when filter changes
        $('select[name="status"], select[name="retail"]').change(function() {
            $(this).closest('form').submit();
        });

        // Confirm delete
        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin menghapus data hutang ini?')) {
                $(this).closest('form').submit();
            }
        });

        // Highlight terlambat rows
        $('tr.table-danger').hover(
            function() {
                $(this).addClass('bg-danger bg-opacity-10');
            },
            function() {
                $(this).removeClass('bg-danger bg-opacity-10');
            }
        );
    });
</script>
@endpush