@extends('layouts.app')

@section('title', 'Edit Hutang Retail - ' . $hutangRetail->no_invoice)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i> Edit Hutang Retail - {{ $hutangRetail->no_invoice }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('hutangretail.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('hutangretail.update', $hutangRetail->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="no_invoice">No Invoice *</label>
                                    <input type="text" class="form-control @error('no_invoice') is-invalid @enderror" 
                                           id="no_invoice" name="no_invoice" value="{{ old('no_invoice', $hutangRetail->no_invoice) }}" required>
                                    @error('no_invoice')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_hutang">Tanggal Hutang *</label>
                                    <input type="date" class="form-control @error('tanggal_hutang') is-invalid @enderror" 
                                           id="tanggal_hutang" name="tanggal_hutang" value="{{ old('tanggal_hutang', $hutangRetail->tanggal_hutang->format('Y-m-d')) }}" required>
                                    @error('tanggal_hutang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_retail">Nama Retail *</label>
                                    <input type="text" class="form-control @error('nama_retail') is-invalid @enderror" 
                                           id="nama_retail" name="nama_retail" value="{{ old('nama_retail', $hutangRetail->nama_retail) }}" 
                                           placeholder="Masukkan nama retail" required>
                                    @error('nama_retail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kontak_retail">Kontak Retail</label>
                                    <input type="text" class="form-control @error('kontak_retail') is-invalid @enderror" 
                                           id="kontak_retail" name="kontak_retail" value="{{ old('kontak_retail', $hutangRetail->kontak_retail) }}"
                                           placeholder="Nomor telepon/whatsapp" maxlength="20">
                                    @error('kontak_retail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stok_ban_id">Pilih Ban *</label>
                                    <select class="form-control @error('stok_ban_id') is-invalid @enderror" id="stok_ban_id" name="stok_ban_id" required>
                                        <option value="">Pilih Ban</option>
                                        @foreach($stokBans as $stokBan)
                                            <option value="{{ $stokBan->id }}" 
                                                    data-stok="{{ $stokBan->stok }}"
                                                    {{ old('stok_ban_id', $hutangRetail->stok_ban_id) == $stokBan->id ? 'selected' : '' }}>
                                                {{ $stokBan->kode_ban }} - {{ $stokBan->nama_ban }} ({{ $stokBan->brand }}) - Stok: {{ $stokBan->stok }} ban
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('stok_ban_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted" id="stok-info">
                                        @if($hutangRetail->stokBan)
                                            Stok saat ini: {{ $hutangRetail->stokBan->stok }} ban
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jumlah_ban">Jumlah Ban yang Dipinjam *</label>
                                    <input type="number" class="form-control @error('jumlah_ban') is-invalid @enderror" 
                                           id="jumlah_ban" name="jumlah_ban" value="{{ old('jumlah_ban', $hutangRetail->jumlah_ban) }}" 
                                           min="1" required>
                                    @error('jumlah_ban')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted" id="jumlah-info">Total hutang dalam jumlah ban</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dibayar">Jumlah Ban yang Dikembalikan *</label>
                                    <input type="number" class="form-control @error('dibayar') is-invalid @enderror" 
                                           id="dibayar" name="dibayar" value="{{ old('dibayar', $hutangRetail->dibayar) }}" 
                                           min="0" required>
                                    @error('dibayar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                    <small class="form-text text-muted">Total ban yang sudah dikembalikan retail</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo</label>
                                    <input type="date" class="form-control @error('tanggal_jatuh_tempo') is-invalid @enderror" 
                                           id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" 
                                           value="{{ old('tanggal_jatuh_tempo', $hutangRetail->tanggal_jatuh_tempo ? $hutangRetail->tanggal_jatuh_tempo->format('Y-m-d') : '') }}"
                                           min="{{ date('Y-m-d') }}">
                                    @error('tanggal_jatuh_tempo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Opsional - untuk monitoring hutang terlambat</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                              id="keterangan" name="keterangan" rows="3" 
                                              placeholder="Keterangan tambahan tentang hutang ini">{{ old('keterangan', $hutangRetail->keterangan) }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Summary Section -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h5 class="card-title">
                                            <i class="fas fa-chart-bar"></i> Ringkasan Hutang
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <div class="border rounded p-3">
                                                    <h6 class="text-primary">Total Dipinjam</h6>
                                                    <h4 id="total-hutang-display" class="text-primary">{{ $hutangRetail->jumlah_ban }}</h4>
                                                    <small class="text-muted">Ban</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border rounded p-3">
                                                    <h6 class="text-success">Dikembalikan</h6>
                                                    <h4 id="dibayar-display" class="text-success">{{ $hutangRetail->dibayar }}</h4>
                                                    <small class="text-muted">Ban</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border rounded p-3">
                                                    <h6 class="text-warning">Sisa Hutang</h6>
                                                    <h4 id="sisa-hutang-display" class="text-warning">{{ $hutangRetail->sisa_hutang }}</h4>
                                                    <small class="text-muted">Ban</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12 text-center">
                                                <strong>Status:</strong>
                                                <span id="status-display" class="badge badge-{{ $hutangRetail->status == 'Lunas' ? 'success' : ($hutangRetail->status == 'Belum Lunas' ? 'warning' : 'danger') }} ml-2">
                                                    {{ $hutangRetail->status }}
                                                    @if($hutangRetail->isTerlambat())
                                                        (Terlambat {{ $hutangRetail->hari_keterlambatan }} hari)
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        @if($hutangRetail->isTerlambat())
                                        <div class="row mt-2">
                                            <div class="col-md-12 text-center">
                                                <small class="text-danger">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    Hutang ini sudah melewati tanggal jatuh tempo
                                                </small>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Warning Message for Stock Changes -->
                        @if($hutangRetail->stokBan)
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Perhatian:</strong> Mengubah jumlah ban atau jenis ban akan mempengaruhi stok secara otomatis.
                                    Sistem akan menyesuaikan stok berdasarkan perubahan yang dilakukan.
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="form-group text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Update Hutang Retail
                            </button>
                            <a href="{{ route('hutangretail.index') }}" class="btn btn-secondary btn-lg">
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

@push('scripts')
<script>
    $(document).ready(function() {
        const stokSelect = $('#stok_ban_id');
        const jumlahInput = $('#jumlah_ban');
        const dibayarInput = $('#dibayar');
        const stokInfo = $('#stok-info');
        const jumlahInfo = $('#jumlah-info');

        function updateStokInfo() {
            const selectedOption = stokSelect.find('option:selected');
            const stokTersedia = selectedOption.data('stok') || 0;
            const jumlah = parseInt(jumlahInput.val()) || 0;
            
            stokInfo.text(`Stok tersedia: ${stokTersedia} ban`);
            
            if (stokTersedia < jumlah) {
                jumlahInfo.html(`<span class="text-danger">Stok tidak mencukupi! Stok tersedia: ${stokTersedia} ban</span>`);
                jumlahInput.addClass('is-invalid');
            } else {
                jumlahInfo.text(`Jumlah ban yang dipinjam: ${jumlah} ban`);
                jumlahInput.removeClass('is-invalid');
            }
        }

        function updateDibayarMax() {
            const jumlah = parseInt(jumlahInput.val()) || 0;
            dibayarInput.attr('max', jumlah);
            
            const dibayar = parseInt(dibayarInput.val()) || 0;
            if (dibayar > jumlah) {
                dibayarInput.val(jumlah);
                calculateSummary();
            }
        }

        function calculateSummary() {
            const jumlahBan = parseInt(jumlahInput.val()) || 0;
            const dibayar = parseInt(dibayarInput.val()) || 0;
            const tanggalJatuhTempo = $('#tanggal_jatuh_tempo').val();
            
            const totalHutang = jumlahBan;
            const sisaHutang = Math.max(0, totalHutang - dibayar);
            
            // Update display
            $('#total-hutang-display').text(totalHutang);
            $('#dibayar-display').text(dibayar);
            $('#sisa-hutang-display').text(sisaHutang);
            
            // Update status
            let status = 'Belum Lunas';
            let statusClass = 'warning';
            
            if (sisaHutang <= 0) {
                status = 'Lunas';
                statusClass = 'success';
            } else if (tanggalJatuhTempo && new Date(tanggalJatuhTempo) < new Date()) {
                status = 'Terlambat';
                statusClass = 'danger';
            }
            
            $('#status-display')
                .removeClass('badge-secondary badge-warning badge-success badge-danger')
                .addClass('badge-' + statusClass)
                .text(status);
            
            // Update sisa hutang display color
            const sisaDisplay = $('#sisa-hutang-display');
            sisaDisplay.removeClass('text-success text-warning text-danger');
            
            if (sisaHutang <= 0) {
                sisaDisplay.addClass('text-success');
            } else if (sisaHutang > 0 && sisaHutang < totalHutang) {
                sisaDisplay.addClass('text-warning');
            } else {
                sisaDisplay.addClass('text-danger');
            }

            // Validate dibayar
            if (dibayar > totalHutang) {
                dibayarInput.addClass('is-invalid');
            } else {
                dibayarInput.removeClass('is-invalid');
            }
        }

        // Event handlers
        stokSelect.on('change', function() {
            updateStokInfo();
            updateDibayarMax();
            calculateSummary();
        });

        jumlahInput.on('input', function() {
            updateStokInfo();
            updateDibayarMax();
            calculateSummary();
        });

        dibayarInput.on('input', function() {
            updateDibayarMax();
            calculateSummary();
        });

        $('#tanggal_jatuh_tempo').on('change', calculateSummary);

        // Initialize
        updateStokInfo();
        updateDibayarMax();
        calculateSummary();

        // Set minimum date for jatuh tempo to today
        const today = new Date().toISOString().split('T')[0];
        $('#tanggal_jatuh_tempo').attr('min', today);
    });
</script>
@endpush