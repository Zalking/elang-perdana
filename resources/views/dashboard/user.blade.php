@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content_header')
    <h1>Dashboard User</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <div class="row">
        <!-- Total Laporan Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $data['totalLaporan'] }}</h3>
                    <p>Total Laporan Saya</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <a href="{{ route('laporan.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Laporan Applied Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $data['laporanByStatus']['Applied'] ?? 0 }}</h3>
                    <p>Applied</p>
                </div>
                <div class="icon">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <a href="{{ route('laporan.index') }}?status=Applied" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Laporan Interview Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $data['laporanByStatus']['Interview'] ?? 0 }}</h3>
                    <p>Interview</p>
                </div>
                <div class="icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <a href="{{ route('laporan.index') }}?status=Interview" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Laporan Accepted Card -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $data['laporanByStatus']['Accepted'] ?? 0 }}</h3>
                    <p>Accepted</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="{{ route('laporan.index') }}?status=Accepted" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart Section -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Statistik Laporan Saya</h3>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Laporan -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Terbaru</h3>
                    <div class="card-tools">
                        <a href="{{ route('laporan.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Tambah Laporan
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th>Perusahaan</th>
                                    <th>Posisi</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['recentLaporan'] as $laporan)
                                <tr>
                                    <td>
                                        <a href="{{ route('laporan.show', $laporan->id) }}">
                                            {{ Str::limit($laporan->nama_perusahaan, 20) }}
                                        </a>
                                    </td>
                                    <td>{{ Str::limit($laporan->posisi, 15) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $laporan->status == 'Accepted' ? 'success' : ($laporan->status == 'Interview' ? 'primary' : ($laporan->status == 'Applied' ? 'warning' : 'danger')) }}">
                                            {{ $laporan->status }}
                                        </span>
                                    </td>
                                    <td>{{ $laporan->tanggal->format('d/m/Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada laporan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('laporan.index') }}" class="btn btn-sm btn-default">
                        Lihat Semua Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('statusChart').getContext('2d');
        var statusData = @json($data['laporanByStatus']);
        
        var labels = ['Applied', 'Interview', 'Rejected', 'Accepted'];
        var data = [
            statusData['Applied'] || 0,
            statusData['Interview'] || 0,
            statusData['Rejected'] || 0,
            statusData['Accepted'] || 0
        ];

        var chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: ['#ffc107', '#007bff', '#dc3545', '#28a745'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script>
@endpush