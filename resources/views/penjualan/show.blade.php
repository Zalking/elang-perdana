@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('content_header')
    <h1>Detail Penjualan</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('penjualan.index') }}">Penjualan</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Penjualan</h3>
            <div class="card-tools">
                <a href="{{ route('penjualan.edit', $penjualan->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('penjualan.index') }}" class="btn btn-default">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">No Faktur</th>
                            <td>{{ $penjualan->no_faktur }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Penjualan</th>
                            <td>{{ $penjualan->tanggal_penjualan->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Nama Pelanggan</th>
                            <td>{{ $penjualan->nama_pelanggan }}</td>
                        </tr>
                        <tr>
                            <th>Nama Barang</th>
                            <td>{{ $penjualan->nama_barang }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah</th>
                            <td>{{ $penjualan->jumlah }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Harga Satuan</th>
                            <td>Rp {{ number_format($penjualan->harga_satuan, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Metode Pembayaran</th>
                            <td>
                                <span class="badge badge-{{ $penjualan->metode_pembayaran == 'Tunai' ? 'success' : ($penjualan->metode_pembayaran == 'Transfer' ? 'primary' : 'warning') }}">
                                    {{ $penjualan->metode_pembayaran }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge badge-{{ $penjualan->status == 'Lunas' ? 'success' : ($penjualan->status == 'Pending' ? 'warning' : 'danger') }}">
                                    {{ $penjualan->status }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Dibuat Oleh</th>
                            <td>{{ $penjualan->user->name }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th width="15%">Keterangan</th>
                            <td>{{ $penjualan->keterangan ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat Pada</th>
                            <td>{{ $penjualan->created_at->format('d F Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Diupdate Pada</th>
                            <td>{{ $penjualan->updated_at->format('d F Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection