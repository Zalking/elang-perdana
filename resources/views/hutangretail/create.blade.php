@extends('layouts.app')

@section('title', 'Tambah Hutang Retail')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus"></i> Tambah Hutang Retail Baru
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('hutangretail.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Debug Info --}}
                    @if(env('APP_DEBUG'))
                    <div class="alert alert-info d-none" id="debug-info">
                        <strong>Debug Info:</strong><br>
                        Total Stok Ban: {{ $stokBans->count() }}<br>
                        @foreach($stokBans->take(3) as $stok)
                            {{ $stok->nama_ban }} - Stok: {{ $stok->stok }}<br>
                        @endforeach
                    </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning">
                            {{ session('warning') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Warning jika tidak ada stok ban --}}
                    @if($stokBans->count() == 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            <strong>Perhatian!</strong> Tidak ada stok ban yang tersedia. 
                            <a href="{{ route('stokban.create') }}" class="alert-link">Tambah stok ban terlebih dahulu</a> 
                            sebelum membuat hutang retail.
                        </div>
                    @endif

                    <form action="{{ route('hutangretail.store') }}" method="POST" id="hutangForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="no_invoice">No Invoice *</label>
                                    <input type="text" class="form-control @error('no_invoice') is-invalid @enderror" 
                                           id="no_invoice" name="no_invoice" value="{{ $noInvoice }}" required readonly>
                                    @error('no_invoice')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_hutang">Tanggal Hutang *</label>
                                    <input type="date" class="form-control @error('tanggal_hutang') is-invalid @enderror" 
                                           id="tanggal_hutang" name="tanggal_hutang" value="{{ old('tanggal_hutang', date('Y-m-d')) }}" required>
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
                                           id="nama_retail" name="nama_retail" value="{{ old('nama_retail') }}" 
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
                                           id="kontak_retail" name="kontak_retail" value="{{ old('kontak_retail') }}" 
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
                                    <select class="form-control @error('stok_ban_id') is-invalid @enderror" 
                                            id="stok_ban_id" name="stok_ban_id" 
                                            {{ $stokBans->count() == 0 ? 'disabled' : 'required' }}>
                                        <option value="">Pilih Ban</option>
                                        @foreach($stokBans as $stokBan)
                                            @php
                                                $stokValue = $stokBan->stok ?? 0;
                                                $isUnlimited = $stokBan->stok === null;
                                                $stokDisplay = $isUnlimited ? 'Unlimited' : $stokValue;
                                            @endphp
                                            <option value="{{ $stokBan->id }}" 
                                                    data-stok="{{ $stokValue }}"
                                                    data-unlimited="{{ $isUnlimited ? '1' : '0' }}"
                                                    {{ old('stok_ban_id') == $stokBan->id ? 'selected' : '' }}>
                                                {{ $stokBan->kode_ban }} - {{ $stokBan->nama_ban }} ({{ $stokBan->brand }}) 
                                                - Stok: {{ $stokDisplay }} ban
                                                @if($isUnlimited)
                                                    <i class="fas fa-infinity text-success"></i>
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($stokBans->count() == 0)
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-circle"></i> 
                                            Tidak ada stok ban yang tersedia. 
                                            <a href="{{ route('stokban.create') }}">Tambah stok ban</a>
                                        </small>
                                    @endif
                                    @error('stok_ban_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted" id="stok-info">
                                        @if($stokBans->count() > 0)
                                            Pilih ban dari stok yang tersedia
                                        @else
                                            <span class="text-danger">Tidak ada stok ban yang tersedia</span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jumlah_ban">Jumlah Ban yang Dipinjam *</label>
                                    <input type="number" class="form-control @error('jumlah_ban') is-invalid @enderror" 
                                           id="jumlah_ban" name="jumlah_ban" value="{{ old('jumlah_ban', 1) }}" 
                                           min="1" 
                                           {{ $stokBans->count() == 0 ? 'disabled' : 'required' }}>
                                    @error('jumlah_ban')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted" id="jumlah-info">
                                        @if($stokBans->count() > 0)
                                            Total hutang dalam jumlah ban
                                        @else
                                            <span class="text-danger">Input dinonaktifkan - tidak ada stok ban</span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dibayar">Jumlah Ban yang Dikembalikan Awal *</label>
                                    <input type="number" class="form-control @error('dibayar') is-invalid @enderror" 
                                           id="dibayar" name="dibayar" value="{{ old('dibayar', 0) }}" 
                                           min="0" 
                                           {{ $stokBans->count() == 0 ? 'disabled' : 'required' }}>
                                    @error('dibayar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted" id="dibayar-info">
                                        @if($stokBans->count() > 0)
                                            Jumlah ban yang langsung dikembalikan retail saat transaksi
                                        @else
                                            <span class="text-danger">Input dinonaktifkan - tidak ada stok ban</span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo</label>
                                    <input type="date" class="form-control @error('tanggal_jatuh_tempo') is-invalid @enderror" 
                                           id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo') }}"
                                           min="{{ date('Y-m-d') }}"
                                           {{ $stokBans->count() == 0 ? 'disabled' : '' }}>
                                    @error('tanggal_jatuh_tempo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Opsional - untuk monitoring hutang terlambat
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                              id="keterangan" name="keterangan" rows="3" 
                                              placeholder="Keterangan tambahan tentang hutang ini"
                                              {{ $stokBans->count() == 0 ? 'disabled' : '' }}>{{ old('keterangan') }}</textarea>
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
                                                    <h4 id="total-hutang-display" class="text-primary">0</h4>
                                                    <small class="text-muted">Ban</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border rounded p-3">
                                                    <h6 class="text-success">Dikembalikan</h6>
                                                    <h4 id="dibayar-display" class="text-success">0</h4>
                                                    <small class="text-muted">Ban</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border rounded p-3">
                                                    <h6 class="text-warning">Sisa Hutang</h6>
                                                    <h4 id="sisa-hutang-display" class="text-warning">0</h4>
                                                    <small class="text-muted">Ban</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12 text-center">
                                                <strong>Status:</strong>
                                                <span id="status-display" class="badge badge-secondary ml-2">Pilih Ban</span>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12 text-center">
                                                <small id="summary-info" class="text-muted">
                                                    @if($stokBans->count() == 0)
                                                        <span class="text-danger">
                                                            <i class="fas fa-exclamation-circle"></i> 
                                                            Tidak dapat membuat ringkasan - tidak ada stok ban tersedia
                                                        </span>
                                                    @else
                                                        Pilih ban dan isi jumlah untuk melihat ringkasan
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg" 
                                    id="submitBtn" 
                                    {{ $stokBans->count() == 0 ? 'disabled' : '' }}>
                                <i class="fas fa-save"></i> Simpan Hutang Retail
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
    // Inisialisasi variabel dari PHP ke JavaScript - FIXED VERSION
    const hasStokBans = <?php echo $stokBans->count() > 0 ? 'true' : 'false'; ?>;
    
    $(document).ready(function() {
        const stokSelect = $('#stok_ban_id');
        const jumlahInput = $('#jumlah_ban');
        const dibayarInput = $('#dibayar');
        const stokInfo = $('#stok-info');
        const jumlahInfo = $('#jumlah-info');
        const submitBtn = $('#submitBtn');
        const form = $('#hutangForm');

        // Check if there are any stok bans available
        if (!hasStokBans) {
            disableForm();
            return;
        }

        function disableForm() {
            stokSelect.prop('disabled', true);
            jumlahInput.prop('disabled', true);
            dibayarInput.prop('disabled', true);
            $('#tanggal_jatuh_tempo').prop('disabled', true);
            $('#keterangan').prop('disabled', true);
            submitBtn.prop('disabled', true);
            
            // Update summary display
            $('#total-hutang-display').text('0');
            $('#dibayar-display').text('0');
            $('#sisa-hutang-display').text('0');
            $('#status-display')
                .removeClass('badge-warning badge-success badge-danger')
                .addClass('badge-secondary')
                .text('Tidak Ada Stok');
        }

        function updateStokInfo() {
            const selectedOption = stokSelect.find('option:selected');
            const stokTersedia = parseInt(selectedOption.data('stok')) || 0;
            const isUnlimited = selectedOption.data('unlimited') === '1';
            const jumlah = parseInt(jumlahInput.val()) || 0;
            
            if (selectedOption.val() === "") {
                stokInfo.text('Pilih ban dari stok yang tersedia');
                jumlahInput.prop('disabled', true);
                jumlahInfo.html('<span class="text-warning">Pilih ban terlebih dahulu</span>');
                submitBtn.prop('disabled', true);
                return;
            } else {
                jumlahInput.prop('disabled', false);
                submitBtn.prop('disabled', false);
            }
            
            if (isUnlimited) {
                stokInfo.html('<span class="text-success"><i class="fas fa-infinity"></i> Stok unlimited - tidak terbatas</span>');
                jumlahInfo.text('Jumlah ban yang akan dipinjam: ' + jumlah + ' ban');
                jumlahInput.removeClass('is-invalid');
            } else {
                stokInfo.text('Stok tersedia: ' + stokTersedia + ' ban');
                
                if (stokTersedia < jumlah) {
                    jumlahInfo.html('<span class="text-danger">Stok tidak mencukupi! Stok tersedia: ' + stokTersedia + ' ban</span>');
                    jumlahInput.addClass('is-invalid');
                    submitBtn.prop('disabled', true);
                } else {
                    jumlahInfo.text('Jumlah ban yang akan dipinjam: ' + jumlah + ' ban');
                    jumlahInput.removeClass('is-invalid');
                    submitBtn.prop('disabled', false);
                }
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
            const selectedBan = stokSelect.val();
            const jumlahBan = parseInt(jumlahInput.val()) || 0;
            const dibayar = parseInt(dibayarInput.val()) || 0;
            const tanggalJatuhTempo = $('#tanggal_jatuh_tempo').val();
            
            // Reset summary jika belum memilih ban
            if (!selectedBan) {
                $('#total-hutang-display').text('0');
                $('#dibayar-display').text('0');
                $('#sisa-hutang-display').text('0');
                $('#status-display')
                    .removeClass('badge-warning badge-success badge-danger')
                    .addClass('badge-secondary')
                    .text('Pilih Ban');
                $('#summary-info').html('<span class="text-warning">Pilih ban terlebih dahulu</span>');
                return;
            }
            
            const totalHutang = jumlahBan;
            const sisaHutang = Math.max(0, totalHutang - dibayar);
            
            // Update display
            $('#total-hutang-display').text(totalHutang);
            $('#dibayar-display').text(dibayar);
            $('#sisa-hutang-display').text(sisaHutang);
            
            // Update status
            let status = 'Belum Lunas';
            let statusClass = 'warning';
            let summaryText = 'Hutang aktif - belum lunas';
            
            if (sisaHutang <= 0 && totalHutang > 0) {
                status = 'Lunas';
                statusClass = 'success';
                summaryText = 'Hutang sudah lunas';
            } else if (tanggalJatuhTempo && new Date(tanggalJatuhTempo) < new Date()) {
                status = 'Terlambat';
                statusClass = 'danger';
                summaryText = 'Hutang terlambat bayar';
            } else if (totalHutang === 0) {
                status = 'Masukkan Jumlah';
                statusClass = 'secondary';
                summaryText = 'Masukkan jumlah ban yang dipinjam';
            }
            
            $('#status-display')
                .removeClass('badge-secondary badge-warning badge-success badge-danger')
                .addClass('badge-' + statusClass)
                .text(status);
            
            // Update sisa hutang display color
            const sisaDisplay = $('#sisa-hutang-display');
            sisaDisplay.removeClass('text-success text-warning text-danger text-secondary');
            
            if (sisaHutang <= 0 && totalHutang > 0) {
                sisaDisplay.addClass('text-success');
            } else if (sisaHutang > 0 && sisaHutang < totalHutang) {
                sisaDisplay.addClass('text-warning');
            } else if (sisaHutang === totalHutang && totalHutang > 0) {
                sisaDisplay.addClass('text-danger');
            } else {
                sisaDisplay.addClass('text-secondary');
            }

            // Update summary info
            $('#summary-info').text(summaryText);

            // Validate dibayar
            if (dibayar > totalHutang) {
                dibayarInput.addClass('is-invalid');
                submitBtn.prop('disabled', true);
            } else {
                dibayarInput.removeClass('is-invalid');
                submitBtn.prop('disabled', false);
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

        // Form submission validation
        form.on('submit', function(e) {
            const selectedBan = stokSelect.val();
            const jumlah = parseInt(jumlahInput.val()) || 0;
            const dibayar = parseInt(dibayarInput.val()) || 0;
            
            if (!selectedBan) {
                e.preventDefault();
                alert('Pilih ban terlebih dahulu!');
                return false;
            }
            
            if (jumlah <= 0) {
                e.preventDefault();
                alert('Jumlah ban harus lebih dari 0!');
                return false;
            }
            
            if (dibayar > jumlah) {
                e.preventDefault();
                alert('Jumlah dikembalikan tidak boleh lebih dari jumlah dipinjam!');
                return false;
            }
        });

        // Initialize
        updateStokInfo();
        updateDibayarMax();
        calculateSummary();

        // Set minimum date for jatuh tempo to today
        const today = new Date().toISOString().split('T')[0];
        $('#tanggal_jatuh_tempo').attr('min', today);

        // Enable debug info with Ctrl+Shift+D
        $(document).on('keydown', function(e) {
            if (e.ctrlKey && e.shiftKey && e.key === 'D') {
                $('#debug-info').toggleClass('d-none');
            }
        });
    });
</script>
@endpush