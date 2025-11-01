<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Penjualan;
use App\Models\StokBan;
use App\Models\HutangRetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->role === 'superadmin') {
            // User Statistics
            $data['totalUsers'] = User::count();
            $data['userStats'] = [
                'superadmin' => User::where('role', 'superadmin')->count(),
                'admin' => User::where('role', 'admin')->count(),
                'user' => User::where('role', 'user')->count()
            ];

            // Sales Statistics
            $data['totalPenjualan'] = Penjualan::sum('total');
            $data['totalTransaksi'] = Penjualan::count();
            $data['penjualanHariIni'] = Penjualan::whereDate('tanggal_penjualan', today())->sum('total');
            $data['pendingTransactions'] = Penjualan::where('status', 'Pending')->count();
            $data['cancelledTransactions'] = Penjualan::where('status', 'Dibatalkan')->count();

            // Stok Ban Statistics
            $data['totalStokBan'] = StokBan::sum('stok');
            $data['totalNilaiStok'] = StokBan::sum('total_nilai_stok');
            $data['lowStockItems'] = StokBan::where('status', 'Hampir Habis')->count();

            // Hutang Statistics
            $data['totalHutang'] = HutangRetail::sum('total_hutang');
            $data['totalSisaHutang'] = HutangRetail::sum('sisa_hutang');
            $data['hutangBelumLunas'] = HutangRetail::where('status', '!=', 'Lunas')->count();

            // Chart data - Last 7 days sales
            $data['salesChartData'] = [
                'labels' => [],
                'data' => []
            ];

            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $total = Penjualan::whereDate('tanggal_penjualan', $date)->sum('total');
                $data['salesChartData']['labels'][] = $date->format('d M');
                $data['salesChartData']['data'][] = $total;
            }

            // Recent transactions
            $data['recentPenjualan'] = Penjualan::with('user')->latest()->take(5)->get();
            
            return view('dashboard.superadmin', compact('data'));
            
        } elseif ($user->role === 'admin') {
            $data['totalLaporan'] = Laporan::count();
            $data['totalPenjualan'] = Penjualan::sum('total');
            $data['totalTransaksi'] = Penjualan::count();
            $data['laporanByStatus'] = Laporan::selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->get()
                ->pluck('total', 'status');
            
            return view('dashboard.admin', compact('data'));
        } else {
            $data['totalLaporan'] = Laporan::where('user_id', $user->id)->count();
            $data['laporanByStatus'] = Laporan::where('user_id', $user->id)
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->get()
                ->pluck('total', 'status');
            
            $data['recentLaporan'] = Laporan::where('user_id', $user->id)
                ->with('user')
                ->latest()
                ->take(5)
                ->get();
            
            return view('dashboard.user', compact('data'));
        }
    }
}