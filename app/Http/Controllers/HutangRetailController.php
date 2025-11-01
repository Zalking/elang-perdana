<?php

namespace App\Http\Controllers;

use App\Models\HutangRetail;
use App\Models\StokBan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HutangRetailController extends Controller
{
    public function index(Request $request)
    {
        $query = HutangRetail::with(['stokBan', 'user']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_invoice', 'like', '%' . $search . '%')
                  ->orWhere('nama_retail', 'like', '%' . $search . '%')
                  ->orWhereHas('stokBan', function($q) use ($search) {
                      $q->where('nama_ban', 'like', '%' . $search . '%')
                        ->orWhere('brand', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->status($request->status); // Gunakan scope status dari model
        }

        if ($request->has('retail') && $request->retail != '') {
            $query->where('nama_retail', 'like', '%' . $request->retail . '%');
        }

        $hutangRetails = $query->latest()->paginate(10);

        // Hitung total dalam jumlah ban menggunakan method dari model
        $statistik = HutangRetail::getStatistik();
        
        $totalHutang = $statistik['total_hutang_ban'];
        $totalDibayar = $statistik['total_dibayar_ban'];
        $totalSisaHutang = $statistik['total_sisa_hutang_ban'];
        $hutangBelumLunas = $statistik['belum_lunas_count'];

        $retails = HutangRetail::select('nama_retail')->distinct()->pluck('nama_retail');

        return view('hutangretail.index', compact(
            'hutangRetails',
            'totalHutang',
            'totalDibayar',
            'totalSisaHutang',
            'hutangBelumLunas',
            'retails',
            'statistik'
        ));
    }

    public function create()
    {
        $stokBans = StokBan::where('stok', '>', 0)->get(); // Hanya tampilkan ban yang ada stoknya
        
        $lastHutang = HutangRetail::latest()->first();
        $nextId = $lastHutang ? $lastHutang->id + 1 : 1;
        $noInvoice = 'HUT-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('hutangretail.create', compact('stokBans', 'noInvoice'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_invoice' => 'required|string|unique:hutang_retails',
            'tanggal_hutang' => 'required|date',
            'nama_retail' => 'required|string|max:255',
            'kontak_retail' => 'nullable|string|max:20',
            'stok_ban_id' => 'required|exists:stok_bans,id',
            'jumlah_ban' => 'required|integer|min:1',
            'dibayar' => 'required|integer|min:0|max:'.$request->jumlah_ban,
            'tanggal_jatuh_tempo' => 'nullable|date|after_or_equal:tanggal_hutang',
            'keterangan' => 'nullable|string',
        ]);

        try {
            // Cek stok tersedia
            $stokBan = StokBan::findOrFail($request->stok_ban_id);
            if ($stokBan->stok < $request->jumlah_ban) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Stok ban tidak mencukupi. Stok tersedia: ' . $stokBan->stok . ' pcs');
            }

            HutangRetail::create([
                'no_invoice' => $request->no_invoice,
                'tanggal_hutang' => $request->tanggal_hutang,
                'nama_retail' => $request->nama_retail,
                'kontak_retail' => $request->kontak_retail,
                'stok_ban_id' => $request->stok_ban_id,
                'jumlah_ban' => $request->jumlah_ban,
                'dibayar' => $request->dibayar,
                'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
                'keterangan' => $request->keterangan,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('hutangretail.index')
                ->with('success', 'Data hutang retail berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan hutang: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $hutangRetail = HutangRetail::with(['stokBan', 'user'])->findOrFail($id);
        return view('hutangretail.show', compact('hutangRetail'));
    }

    public function edit($id)
    {
        $hutangRetail = HutangRetail::findOrFail($id);
        $stokBans = StokBan::all();
        return view('hutangretail.edit', compact('hutangRetail', 'stokBans'));
    }

    public function update(Request $request, $id)
    {
        $hutangRetail = HutangRetail::findOrFail($id);

        $request->validate([
            'no_invoice' => 'required|string|unique:hutang_retails,no_invoice,' . $hutangRetail->id,
            'tanggal_hutang' => 'required|date',
            'nama_retail' => 'required|string|max:255',
            'kontak_retail' => 'nullable|string|max:20',
            'stok_ban_id' => 'required|exists:stok_bans,id',
            'jumlah_ban' => 'required|integer|min:1',
            'dibayar' => 'required|integer|min:0|max:'.$request->jumlah_ban,
            'tanggal_jatuh_tempo' => 'nullable|date|after_or_equal:tanggal_hutang',
            'keterangan' => 'nullable|string',
        ]);

        try {
            // Update data - biarkan model handle logika stok melalui boot method
            $hutangRetail->update($request->all());

            return redirect()->route('hutangretail.index')
                ->with('success', 'Data hutang retail berhasil diupdate!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate hutang: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $hutangRetail = HutangRetail::findOrFail($id);
            $hutangRetail->delete();

            return redirect()->route('hutangretail.index')
                ->with('success', 'Data hutang retail berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus hutang: ' . $e->getMessage());
        }
    }

    public function bayarHutang(Request $request, $id)
    {
        $hutangRetail = HutangRetail::findOrFail($id);

        $request->validate([
            'jumlah_bayar' => 'required|integer|min:1|max:' . $hutangRetail->sisa_hutang,
        ]);

        try {
            // Gunakan method bayar dari model yang sudah handle stok otomatis
            $hutangRetail->bayar($request->jumlah_bayar);

            return redirect()->back()
                ->with('success', 'Pengembalian ' . $request->jumlah_bayar . ' ban berhasil dicatat!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mencatat pengembalian: ' . $e->getMessage());
        }
    }

    public function dashboard()
    {
        // Gunakan method statistik dari model
        $statistik = HutangRetail::getStatistik();
        
        $totalHutang = $statistik['total_hutang_ban'];
        $totalDibayar = $statistik['total_dibayar_ban'];
        $totalSisaHutang = $statistik['total_sisa_hutang_ban'];
        $hutangBelumLunas = $statistik['belum_lunas_count'];
        $hutangTerlambat = $statistik['terlambat_count'];

        $recentHutang = HutangRetail::with('stokBan')->latest()->take(5)->get();

        // Data untuk chart
        $hutangByStatus = [
            'Belum Lunas' => $statistik['belum_lunas_count'],
            'Terlambat' => $statistik['terlambat_count'],
            'Lunas' => $statistik['lunas_count']
        ];

        // Top retail dengan hutang terbanyak
        $topRetail = HutangRetail::select('nama_retail')
            ->selectRaw('SUM(jumlah_ban - dibayar) as total_sisa_hutang')
            ->whereRaw('jumlah_ban > dibayar')
            ->groupBy('nama_retail')
            ->orderByDesc('total_sisa_hutang')
            ->take(5)
            ->get();

        return view('hutangretail.dashboard', compact(
            'totalHutang',
            'totalDibayar',
            'totalSisaHutang',
            'hutangBelumLunas',
            'hutangTerlambat',
            'recentHutang',
            'hutangByStatus',
            'statistik',
            'topRetail'
        ));
    }

    // API untuk mendapatkan data hutang
    public function apiHutangRetail(Request $request)
    {
        $query = HutangRetail::with(['stokBan', 'user']);

        if ($request->has('status') && $request->status != '') {
            $query->status($request->status);
        }

        if ($request->has('retail') && $request->retail != '') {
            $query->where('nama_retail', $request->retail);
        }

        $hutangRetails = $query->latest()->get();

        return response()->json([
            'data' => $hutangRetails,
            'statistik' => HutangRetail::getStatistik()
        ]);
    }

    // Method untuk export laporan
    public function exportLaporan(Request $request)
    {
        $query = HutangRetail::with(['stokBan', 'user']);

        if ($request->has('start_date') && $request->start_date != '') {
            $query->where('tanggal_hutang', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->where('tanggal_hutang', '<=', $request->end_date);
        }

        if ($request->has('status') && $request->status != '') {
            $query->status($request->status);
        }

        $hutangRetails = $query->latest()->get();

        // Return view untuk PDF atau Excel
        return view('hutangretail.export', compact('hutangRetails'));
    }

    // Method untuk mendapatkan sisa hutang per retail
    public function sisaHutangPerRetail()
    {
        $sisaHutang = HutangRetail::select('nama_retail')
            ->selectRaw('SUM(jumlah_ban) as total_hutang')
            ->selectRaw('SUM(dibayar) as total_dibayar')
            ->selectRaw('SUM(jumlah_ban - dibayar) as sisa_hutang')
            ->groupBy('nama_retail')
            ->havingRaw('SUM(jumlah_ban - dibayar) > 0')
            ->orderByDesc('sisa_hutang')
            ->get();

        return response()->json([
            'data' => $sisaHutang,
            'status' => 'success'
        ]);
    }
}