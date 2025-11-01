@extends('layouts.app')

@section('title', 'Data Stok Ban')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-boxes"></i> Data Stok Ban
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('stokban.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Stok Ban
                        </a>
                        <a href="{{ route('stokban.dashboard') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form action="{{ route('stokban.index') }}" method="GET" class="form-inline">
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <input type="text" name="search" class="form-control" 
                                               placeholder="Cari kode/nama/ukuran..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <select name="brand" class="form-control">
                                            <option value="">Semua Brand</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                                                    {{ $brand }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <select name="status" class="form-control">
                                            <option value="">Semua Status</option>
                                            <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                                            <option value="Hampir Habis" {{ request('status') == 'Hampir Habis' ? 'selected' : '' }}>Hampir Habis</option>
                                            <option value="Habis" {{ request('status') == 'Habis' ? 'selected' : '' }}>Habis</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <select name="type" class="form-control">
                                            <option value="">Semua Type</option>
                                            <option value="Tube" {{ request('type') == 'Tube' ? 'selected' : '' }}>Tube</option>
                                            <option value="Tubeless" {{ request('type') == 'Tubeless' ? 'selected' : '' }}>Tubeless</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Filter
                                        </button>
                                        <a href="{{ route('stokban.index') }}" class="btn btn-secondary">
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
                                    <h3>{{ number_format($totalStok) }}</h3>
                                    <p>Total Stok</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-boxes"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>Rp {{ number_format($totalNilaiStok, 0, ',', '.') }}</h3>
                                    <p>Total Nilai Stok</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $lowStock }}</h3>
                                    <p>Stok Hampir Habis</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $outOfStock }}</h3>
                                    <p>Stok Habis</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Kode Ban</th>
                                    <th>Nama Ban</th>
                                    <th>Brand</th>
                                    <th>Ukuran</th>
                                    <th>Type</th>
                                    <th>Stok</th>
                                    <th>Harga Jual</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stokBans as $stokBan)
                                <tr>
                                    <td>{{ $loop->iteration + ($stokBans->currentPage() - 1) * $stokBans->perPage() }}</td>
                                    <td>
                                        <span class="badge badge-primary">{{ $stokBan->kode_ban }}</span>
                                    </td>
                                    <td>{{ $stokBan->nama_ban }}</td>
                                    <td>{{ $stokBan->brand }}</td>
                                    <td>{{ $stokBan->ukuran }}</td>
                                    <td>{{ $stokBan->type }}</td>
                                    <td>
                                        <span class="badge badge-{{ $stokBan->stok > $stokBan->minimum_stok ? 'success' : ($stokBan->stok == 0 ? 'danger' : 'warning') }}">
                                            {{ $stokBan->stok }}
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($stokBan->harga_jual, 0, ',', '.') }}</td>
                                    <td>
                                        @if($stokBan->status == 'Tersedia')
                                            <span class="badge badge-success">Tersedia</span>
                                        @elseif($stokBan->status == 'Hampir Habis')
                                            <span class="badge badge-warning">Hampir Habis</span>
                                        @else
                                            <span class="badge badge-danger">Habis</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('stokban.show', $stokBan->id) }}" class="btn btn-info btn-sm" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('stokban.edit', $stokBan->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('stokban.destroy', $stokBan->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus" 
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus stok ban ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">
                                        <i class="fas fa-box-open fa-3x mb-3"></i>
                                        <br>
                                        <h5>Tidak ada data stok ban ditemukan</h5>
                                        <p>Silakan tambah data stok ban baru</p>
                                        <a href="{{ route('stokban.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Stok Ban
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
                            Menampilkan {{ $stokBans->firstItem() ?? 0 }} - {{ $stokBans->lastItem() ?? 0 }} dari {{ $stokBans->total() }} data
                        </div>
                        <div>
                            {{ $stokBans->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Auto submit form when filter changes
        $('select[name="brand"], select[name="status"], select[name="type"]').change(function() {
            $(this).closest('form').submit();
        });
    });
</script>
@endsection