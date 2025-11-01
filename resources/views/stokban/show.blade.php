@extends('layouts.app')

@section('title', 'Detail Stok Ban - ' . $stokBan->kode_ban)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Detail Stok Ban - {{ $stokBan->kode_ban }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('stokban.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('stokban.edit', $stokBan->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Kode Ban</th>
                                    <td>
                                        <span class="badge badge-primary badge-lg">{{ $stokBan->kode_ban }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nama Ban</th>
                                    <td>{{ $stokBan->nama_ban }}</td>
                                </tr>
                                <tr>
                                    <th>Brand</th>
                                    <td>{{ $stokBan->brand }}</td>
                                </tr>
                                <tr>
                                    <th>Ukuran</th>
                                    <td>{{ $stokBan->ukuran }}</td>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <td>{{ $stokBan->type }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Stok Saat Ini</th>
                                    <td>
                                        <span class="badge badge-{{ $stokBan->stok > $stokBan->minimum_stok ? 'success' : ($stokBan->stok == 0 ? 'danger' : 'warning') }} badge-lg" style="font-size: 1.1em;">
                                            {{ $stokBan->stok }} pcs
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($stokBan->status == 'Tersedia')
                                            <span class="badge badge-success">Tersedia</span>
                                        @elseif($stokBan->status == 'Hampir Habis')
                                            <span class="badge badge-warning">Hampir Habis</span>
                                        @else
                                            <span class="badge badge-danger">Habis</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Harga Beli</th>
                                    <td>Rp {{ number_format($stokBan->harga_beli, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Harga Jual</th>
                                    <td>Rp {{ number_format($stokBan->harga_jual, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Minimum Stok</th>
                                    <td>{{ $stokBan->minimum_stok }} pcs</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($stokBan->deskripsi)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Deskripsi</h5>
                                </div>
                                <div class="card-body">
                                    {{ $stokBan->deskripsi }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Informasi Nilai Stok -->
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-box"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Nilai Stok</span>
                                    <span class="info-box-number">Rp {{ number_format($stokBan->total_nilai_stok, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Profit per Item</span>
                                    <span class="info-box-number">Rp {{ number_format($stokBan->harga_jual - $stokBan->harga_beli, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Margin</span>
                                    <span class="info-box-number">
                                        {{ number_format((($stokBan->harga_jual - $stokBan->harga_beli) / $stokBan->harga_beli) * 100, 2) }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <a href="{{ route('stokban.edit', $stokBan->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Data
                            </a>
                            <form action="{{ route('stokban.destroy', $stokBan->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus stok ban ini?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                            <a href="{{ route('stokban.index') }}" class="btn btn-secondary">
                                <i class="fas fa-list"></i> Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection