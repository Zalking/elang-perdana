@extends('layouts.app')

@section('title', 'Tambah Stok Ban')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus"></i> Tambah Stok Ban Baru
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('stokban.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('stokban.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_ban">Kode Ban *</label>
                                    <input type="text" class="form-control @error('kode_ban') is-invalid @enderror" 
                                           id="kode_ban" name="kode_ban" value="{{ $kodeBan }}" required readonly>
                                    @error('kode_ban')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Kode ban otomatis</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_ban">Nama Ban *</label>
                                    <input type="text" class="form-control @error('nama_ban') is-invalid @enderror" 
                                           id="nama_ban" name="nama_ban" value="{{ old('nama_ban') }}" 
                                           placeholder="Masukkan nama ban" required>
                                    @error('nama_ban')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="brand">Brand *</label>
                                    <select class="form-control @error('brand') is-invalid @enderror" id="brand" name="brand" required>
                                        <option value="">Pilih Brand</option>
                                        <option value="Bridgestone" {{ old('brand') == 'Bridgestone' ? 'selected' : '' }}>Bridgestone</option>
                                        <option value="Michelin" {{ old('brand') == 'Michelin' ? 'selected' : '' }}>Michelin</option>
                                        <option value="Goodyear" {{ old('brand') == 'Goodyear' ? 'selected' : '' }}>Goodyear</option>
                                        <option value="Dunlop" {{ old('brand') == 'Dunlop' ? 'selected' : '' }}>Dunlop</option>
                                        <option value="Yokohama" {{ old('brand') == 'Yokohama' ? 'selected' : '' }}>Yokohama</option>
                                        <option value="GT Radial" {{ old('brand') == 'GT Radial' ? 'selected' : '' }}>GT Radial</option>
                                        <option value="Achilles" {{ old('brand') == 'Achilles' ? 'selected' : '' }}>Achilles</option>
                                        <option value="Accelera" {{ old('brand') == 'Accelera' ? 'selected' : '' }}>Accelera</option>
                                        <option value="Federal" {{ old('brand') == 'Federal' ? 'selected' : '' }}>Federal</option>
                                    </select>
                                    @error('brand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ukuran">Ukuran *</label>
                                    <input type="text" class="form-control @error('ukuran') is-invalid @enderror" 
                                           id="ukuran" name="ukuran" value="{{ old('ukuran') }}" 
                                           placeholder="Contoh: 175/65 R14" required>
                                    @error('ukuran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Type *</label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Pilih Type</option>
                                        <option value="Tube" {{ old('type') == 'Tube' ? 'selected' : '' }}>Tube</option>
                                        <option value="Tubeless" {{ old('type') == 'Tubeless' ? 'selected' : '' }}>Tubeless</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stok">Stok Awal *</label>
                                    <input type="number" class="form-control @error('stok') is-invalid @enderror" 
                                           id="stok" name="stok" value="{{ old('stok', 0) }}" min="0" required>
                                    @error('stok')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="harga_beli">Harga Beli *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" class="form-control @error('harga_beli') is-invalid @enderror" 
                                               id="harga_beli" name="harga_beli" value="{{ old('harga_beli') }}" 
                                               min="0" step="1000" required>
                                    </div>
                                    @error('harga_beli')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="harga_jual">Harga Jual *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" class="form-control @error('harga_jual') is-invalid @enderror" 
                                               id="harga_jual" name="harga_jual" value="{{ old('harga_jual') }}" 
                                               min="0" step="1000" required>
                                    </div>
                                    @error('harga_jual')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="minimum_stok">Minimum Stok *</label>
                                    <input type="number" class="form-control @error('minimum_stok') is-invalid @enderror" 
                                           id="minimum_stok" name="minimum_stok" value="{{ old('minimum_stok', 5) }}" 
                                           min="1" required>
                                    <small class="form-text text-muted">Stok minimum sebelum muncul peringatan</small>
                                    @error('minimum_stok')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                              id="deskripsi" name="deskripsi" rows="3" 
                                              placeholder="Deskripsi tambahan tentang ban">{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Simpan Stok Ban
                            </button>
                            <a href="{{ route('stokban.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Auto calculate harga jual jika harga beli diisi
        $('#harga_beli').on('change', function() {
            const hargaBeli = parseInt($(this).val()) || 0;
            const hargaJual = hargaBeli * 1.2; // 20% markup
            $('#harga_jual').val(Math.round(hargaJual / 1000) * 1000);
        });
    });
</script>
@endsection