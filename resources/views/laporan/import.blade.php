@extends('layouts.app')

@section('title', 'Import Laporan')

@section('content_header')
    <h1>Import Laporan dari Excel</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}">Laporan</a></li>
    <li class="breadcrumb-item active">Import</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="alert alert-info">
                <h5><i class="fas fa-info-circle"></i> Petunjuk Import</h5>
                <p>File Excel harus memiliki format dengan kolom berikut:</p>
                <ul>
                    <li><strong>tanggal</strong> - Format tanggal (YYYY-MM-DD)</li>
                    <li><strong>nama_perusahaan</strong> - Nama perusahaan</li>
                    <li><strong>posisi</strong> - Posisi yang dilamar</li>
                    <li><strong>status</strong> - Applied, Interview, Rejected, atau Accepted</li>
                    <li><strong>keterangan</strong> - Keterangan tambahan (opsional)</li>
                </ul>
            </div>

            <form action="{{ route('laporan.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="file">File Excel</label>
                    <input type="file" name="file" id="file" class="form-control-file @error('file') is-invalid @enderror" accept=".xlsx,.xls,.csv" required>
                    @error('file')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload"></i> Import Data
                    </button>
                    <a href="{{ route('laporan.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>

            @if(session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
@endsection