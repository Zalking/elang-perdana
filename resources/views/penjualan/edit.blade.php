@extends('layouts.app')

@section('title', 'Edit Penjualan')

@section('content_header')
    <h1>Edit Penjualan</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('penjualan.index') }}">Penjualan</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('penjualan.update', $penjualan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_faktur">No Faktur</label>
                            <input type="text" name="no_faktur" id="no_faktur" class="form-control @error('no_faktur') is-invalid @enderror" value="{{ old('no_faktur', $penjualan->no_faktur) }}" required>
                            @error('no_faktur')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal_penjualan">Tanggal Penjualan</label>
                            <input type="date" name="tanggal_penjualan" id="tanggal_penjualan" class="form-control @error('tanggal_penjualan') is-invalid @enderror" value="{{ old('tanggal_penjualan', $penjualan->tanggal_penjualan->format('Y-m-d')) }}" required>
                            @error('tanggal_penjualan')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nama_pelanggan">Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="form-control @error('nama_pelanggan') is-invalid @enderror" value="{{ old('nama_pelanggan', $penjualan->nama_pelanggan) }}" required>
                    @error('nama_pelanggan')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" name="nama_barang" id="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" value="{{ old('nama_barang', $penjualan->nama_barang) }}" required>
                    @error('nama_barang')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="jumlah">Jumlah</label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah', $penjualan->jumlah) }}" min="1" required>
                            @error('jumlah')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="harga_satuan">Harga Satuan</label>
                            <input type="number" name="harga_satuan" id="harga_satuan" class="form-control @error('harga_satuan') is-invalid @enderror" value="{{ old('harga_satuan', $penjualan->harga_satuan) }}" step="0.01" min="0" required>
                            @error('harga_satuan')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="total">Total</label>
                            <input type="text" class="form-control" value="Rp {{ number_format($penjualan->total, 0, ',', '.') }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="metode_pembayaran">Metode Pembayaran</label>
                            <select name="metode_pembayaran" id="metode_pembayaran" class="form-control @error('metode_pembayaran') is-invalid @enderror" required>
                                <option value="">Pilih Metode</option>
                                <option value="Tunai" {{ old('metode_pembayaran', $penjualan->metode_pembayaran) == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                <option value="Transfer" {{ old('metode_pembayaran', $penjualan->metode_pembayaran) == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="Kredit" {{ old('metode_pembayaran', $penjualan->metode_pembayaran) == 'Kredit' ? 'selected' : '' }}>Kredit</option>
                            </select>
                            @error('metode_pembayaran')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="">Pilih Status</option>
                                <option value="Lunas" {{ old('status', $penjualan->status) == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="Pending" {{ old('status', $penjualan->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Dibatalkan" {{ old('status', $penjualan->status) == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan', $penjualan->keterangan) }}</textarea>
                    @error('keterangan')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection