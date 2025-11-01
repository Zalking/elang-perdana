@extends('layouts.app')

@section('title', 'Data Laporan')

@section('content_header')
    <h1>Data Laporan</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Laporan</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="card-title">Daftar Laporan</h3>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('laporan.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Laporan
                    </a>
                    <a href="{{ route('laporan.import.form') }}" class="btn btn-success">
                        <i class="fas fa-upload"></i> Import Excel
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('laporan.index') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="Applied" {{ request('status') == 'Applied' ? 'selected' : '' }}>Applied</option>
                            <option value="Interview" {{ request('status') == 'Interview' ? 'selected' : '' }}>Interview</option>
                            <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="Accepted" {{ request('status') == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" placeholder="Dari Tanggal">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" placeholder="Sampai Tanggal">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('laporan.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>

            <!-- Data Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Perusahaan</th>
                            <th>Posisi</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporans as $laporan)
                        <tr>
                            <td>{{ $laporan->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $laporan->nama_perusahaan }}</td>
                            <td>{{ $laporan->posisi }}</td>
                            <td>
                                <span class="badge badge-{{ $laporan->status == 'Accepted' ? 'success' : ($laporan->status == 'Interview' ? 'primary' : ($laporan->status == 'Applied' ? 'warning' : 'danger')) }}">
                                    {{ $laporan->status }}
                                </span>
                            </td>
                            <td>{{ Str::limit($laporan->keterangan, 50) ?: '-' }}</td>
                            <td>{{ $laporan->user->name }}</td>
                            <td>
                                <a href="{{ route('laporan.show', $laporan->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('laporan.edit', $laporan->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('laporan.destroy', $laporan->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus laporan?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data laporan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $laporans->links() }}
            </div>
        </div>
    </div>
@endsection