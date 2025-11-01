@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('content_header')
    <h1>Detail Laporan</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}">Laporan</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Laporan</h3>
            <div class="card-tools">
                <a href="{{ route('laporan.edit', $laporan->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('laporan.index') }}" class="btn btn-default">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Tanggal</th>
                            <td>{{ $laporan->tanggal->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Nama Perusahaan</th>
                            <td>{{ $laporan->nama_perusahaan }}</td>
                        </tr>
                        <tr>
                            <th>Posisi</th>
                            <td>{{ $laporan->posisi }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge badge-{{ $laporan->status == 'Accepted' ? 'success' : ($laporan->status == 'Interview' ? 'primary' : ($laporan->status == 'Applied' ? 'warning' : 'danger')) }}">
                                    {{ $laporan->status }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Keterangan</th>
                            <td>{{ $laporan->keterangan ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat Oleh</th>
                            <td>{{ $laporan->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat Pada</th>
                            <td>{{ $laporan->created_at->format('d F Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Diupdate Pada</th>
                            <td>{{ $laporan->updated_at->format('d F Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection