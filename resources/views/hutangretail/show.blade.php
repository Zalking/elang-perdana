@extends('layouts.app')

@section('title', 'Detail Hutang Retail')

@section('content_header')
    <h1>Detail Hutang Retail</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('hutangretail.index') }}">Hutang Retail</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Hutang</h3>
            <div class="card-tools">
                <a href="{{ route('hutangretail.edit', $hutangRetail->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('hutangretail.index') }}" class="btn btn-default">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">No Invoice</th>
                            <td>{{ $hutangRetail->no_invoice }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Hutang</th>
                            <td>{{ $hutangRetail->tanggal_hutang->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Nama Retail</th>
                            <td>{{ $hutangRetail->nama_retail }}</td>
                        </tr>
                        <tr>
                            <th>Kontak Retail</th>
                            <td>{{ $hutangRetail->kontak_retail ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Ban</th>
                            <td>{{ $hutangRetail->stokBan->nama_ban }} - {{ $hutangRetail->stokBan->brand }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Jumlah Ban</th>
                            <td>{{ $hutangRetail->jumlah_ban }}</td>
                        </tr>
                        <tr>
                            <th>Harga per Ban</th>
                            <td>Rp {{ number_format($hutangRetail->harga_per_ban, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Total Hutang</th>
                            <td>Rp {{ number_format($hutangRetail->total_hutang, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Dibayar</th>
                            <td>Rp {{ number_format($hutangRetail->dibayar, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Sisa Hutang</th>
                            <td>Rp {{ number_format($hutangRetail->sisa_hutang, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge badge-{{ $hutangRetail->status == 'Lunas' ? 'success' : ($hutangRetail->status == 'Belum Lunas' ? 'warning' : 'danger') }}">
                                    {{ $hutangRetail->status }}
                                </span>
                            </td>
                        </tr>
                        @if($hutangRetail->tanggal_jatuh_tempo)
                        <tr>
                            <th>Jatuh Tempo</th>
                            <td>{{ $hutangRetail->tanggal_jatuh_tempo->format('d F Y') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th width="15%">Keterangan</th>
                            <td>{{ $hutangRetail->keterangan ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat Oleh</th>
                            <td>{{ $hutangRetail->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat Pada</th>
                            <td>{{ $hutangRetail->created_at->format('d F Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Diupdate Pada</th>
                            <td>{{ $hutangRetail->updated_at->format('d F Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Form Pembayaran -->
            @if($hutangRetail->sisa_hutang > 0)
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Bayar Hutang</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('hutangretail.bayar', $h