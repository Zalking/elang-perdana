<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Penjualan::with('user');

        // Filter role user
        if ($user->role === 'user') {
            $query->where('user_id', $user->id);
        }

        // Filter pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_faktur', 'like', '%' . $search . '%')
                  ->orWhere('nama_pelanggan', 'like', '%' . $search . '%')
                  ->orWhere('nama_barang', 'like', '%' . $search . '%');
            });
        }

        // Filter status (sesuai tampilan: "Semua Status")
        if ($request->has('status') && $request->status != '' && $request->status != 'Semua Status') {
            $query->where('status', $request->status);
        }

        // Filter metode pembayaran (sesuai tampilan: "Semua Metode")
        if ($request->has('metode_pembayaran') && $request->metode_pembayaran != '' && $request->metode_pembayaran != 'Semua Metode') {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        // Filter tanggal
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('tanggal_penjualan', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('tanggal_penjualan', '<=', $request->end_date);
        }

        // Clone query untuk menghitung total sebelum pagination
        $totalQuery = clone $query;
        
        $penjualans = $query->latest()->paginate(10);

        // Hitung totals dari query yang difilter
        $totalPenjualan = $totalQuery->sum('total');
        $totalTransaksi = $totalQuery->count();
        
        // Penjualan hari ini (tanpa filter)
        $penjualanHariIni = Penjualan::when($user->role === 'user', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->whereDate('tanggal_penjualan', today())->sum('total');

        return view('penjualan.index', compact(
            'penjualans', 
            'totalPenjualan', 
            'totalTransaksi', 
            'penjualanHariIni'
        ));
    }

    public function create()
    {
        $lastPenjualan = Penjualan::latest()->first();
        $nextId = $lastPenjualan ? $lastPenjualan->id + 1 : 1;
        $noFaktur = 'INV-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('penjualan.create', compact('noFaktur'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_penjualan' => 'required|date',
            'no_faktur' => 'required|string|unique:penjualans',
            'nama_pelanggan' => 'required|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:Tunai,Transfer,Kredit',
            'status' => 'required|in:Lunas,Pending,Dibatalkan',
            'keterangan' => 'nullable|string',
        ]);

        // Hitung total otomatis
        $total = $request->jumlah * $request->harga_satuan;

        Penjualan::create([
            'tanggal_penjualan' => $request->tanggal_penjualan,
            'no_faktur' => $request->no_faktur,
            'nama_pelanggan' => $request->nama_pelanggan,
            'nama_barang' => $request->nama_barang,
            'jumlah' => $request->jumlah,
            'harga_satuan' => $request->harga_satuan,
            'total' => $total, // Ditambahkan
            'metode_pembayaran' => $request->metode_pembayaran,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('penjualan.index')
            ->with('success', 'Data penjualan berhasil ditambahkan!');
    }

    public function show($id)
    {
        $penjualan = Penjualan::with('user')->findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'user' && $penjualan->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('penjualan.show', compact('penjualan'));
    }

    public function edit($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'user' && $penjualan->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('penjualan.edit', compact('penjualan'));
    }

    public function update(Request $request, $id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'user' && $penjualan->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'tanggal_penjualan' => 'required|date',
            'no_faktur' => 'required|string|unique:penjualans,no_faktur,' . $penjualan->id,
            'nama_pelanggan' => 'required|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:Tunai,Transfer,Kredit',
            'status' => 'required|in:Lunas,Pending,Dibatalkan',
            'keterangan' => 'nullable|string',
        ]);

        // Hitung total otomatis
        $total = $request->jumlah * $request->harga_satuan;

        $data = $request->all();
        $data['total'] = $total;

        $penjualan->update($data);

        return redirect()->route('penjualan.index')
            ->with('success', 'Data penjualan berhasil diupdate!');
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'user' && $penjualan->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus data penjualan ini.');
        }

        $penjualan->delete();

        return redirect()->route('penjualan.index')
            ->with('success', 'Data penjualan berhasil dihapus!');
    }

    public function dashboard()
{
    $user = Auth::user();
    $query = Penjualan::query();

    if ($user->role === 'user') {
        $query->where('user_id', $user->id);
    }

    $totalPenjualan = $query->sum('total');
    $totalTransaksi = $query->count();
    
    // Hitung total quantity (jumlah barang dalam pcs)
    $totalQuantity = $query->sum('jumlah');
    
    // Hitung rata-rata penjualan per bulan
    $rataRataPenjualan = 0;
    $totalBulan = Penjualan::when($user->role === 'user', function($q) use ($user) {
        $q->where('user_id', $user->id);
    })->distinct()->selectRaw('YEAR(tanggal_penjualan) as year, MONTH(tanggal_penjualan) as month')->count();
    
    if ($totalBulan > 0) {
        $rataRataPenjualan = $totalPenjualan / $totalBulan;
    }

    $penjualanHariIni = Penjualan::when($user->role === 'user', function($q) use ($user) {
        $q->where('user_id', $user->id);
    })->whereDate('tanggal_penjualan', today())->sum('total');
    
    $penjualanBulanIni = Penjualan::when($user->role === 'user', function($q) use ($user) {
        $q->where('user_id', $user->id);
    })->whereYear('tanggal_penjualan', date('Y'))
      ->whereMonth('tanggal_penjualan', date('m'))
      ->sum('total');

    // Chart data 7 hari terakhir - untuk grafik kombinasi
    $chartData = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::now()->subDays($i);
        
        // Data untuk hari tertentu
        $dayQuery = Penjualan::when($user->role === 'user', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->whereDate('tanggal_penjualan', $date);
        
        $totalHari = $dayQuery->sum('total');
        $quantityHari = $dayQuery->sum('jumlah');
        
        $chartData['labels'][] = $date->format('d M');
        $chartData['quantity'][] = $quantityHari; // Untuk grafik batang (pcs)
        $chartData['total'][] = $totalHari; // Untuk grafik line (rupiah)
    }

    // Data untuk rata-rata per bulan (contoh data statis atau hitung dari database)
    $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $bulanAverages = [];
    
    for ($i = 1; $i <= 12; $i++) {
        $bulanData = Penjualan::when($user->role === 'user', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->whereYear('tanggal_penjualan', date('Y'))
          ->whereMonth('tanggal_penjualan', $i)
          ->get();
        
        if ($bulanData->count() > 0) {
            $bulanAverages[] = $bulanData->sum('total') / $bulanData->count();
        } else {
            $bulanAverages[] = 0;
        }
    }

    $recentPenjualan = $query->with('user')->latest()->take(5)->get();

    return view('penjualan.dashboard', compact(
        'totalPenjualan', 
        'totalTransaksi', 
        'totalQuantity',
        'rataRataPenjualan',
        'penjualanHariIni',
        'penjualanBulanIni',
        'chartData',
        'recentPenjualan',
        'bulanLabels',
        'bulanAverages'
    ));
}
}