<?php

namespace App\Http\Controllers;

use App\Models\StokBan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StokBanController extends Controller
{
    public function index(Request $request)
    {
        // PERBAIKAN: Gunakan scope dari model untuk query yang lebih bersih
        $query = StokBan::query()
            ->search($request->search)
            ->byBrand($request->brand)
            ->byStatus($request->status)
            ->byType($request->type);

        $stokBans = $query->latest()->paginate(10);

        // PERBAIKAN: Gunakan static methods dari model
        $totalStok = StokBan::getTotalStok();
        $totalNilaiStok = StokBan::getTotalNilaiStok();
        $lowStock = StokBan::getLowStockCount();
        $outOfStock = StokBan::getOutOfStockCount();
        $brands = StokBan::select('brand')->distinct()->pluck('brand');

        return view('stokban.index', compact(
            'stokBans', 
            'totalStok', 
            'totalNilaiStok', 
            'lowStock', 
            'outOfStock',
            'brands'
        ));
    }

    public function create()
    {
        // PERBAIKAN: Gunakan method generateKodeBan dari model
        $kodeBan = StokBan::generateKodeBan();

        return view('stokban.create', compact('kodeBan'));
    }

    public function store(Request $request)
    {
        // PERBAIKAN: Gunakan validation rules dari model
        $request->validate(StokBan::getValidationRules());

        // PERBAIKAN: Tidak perlu manual calculate status, model akan handle otomatis
        StokBan::create($request->all());

        return redirect()->route('stokban.index')
            ->with('success', 'Data stok ban berhasil ditambahkan!');
    }

    public function show($id)
    {
        $stokBan = StokBan::with('hutangRetails')->findOrFail($id);
        return view('stokban.show', compact('stokBan'));
    }

    public function edit($id)
    {
        $stokBan = StokBan::findOrFail($id);
        return view('stokban.edit', compact('stokBan'));
    }

    public function update(Request $request, $id)
    {
        $stokBan = StokBan::findOrFail($id);

        // PERBAIKAN: Gunakan validation rules dari model dengan ID
        $request->validate(StokBan::getValidationRules($stokBan->id));

        // PERBAIKAN: Tidak perlu manual calculate status, model akan handle otomatis
        $stokBan->update($request->all());

        return redirect()->route('stokban.index')
            ->with('success', 'Data stok ban berhasil diupdate!');
    }

    public function destroy($id)
    {
        $stokBan = StokBan::findOrFail($id);
        $stokBan->delete();

        return redirect()->route('stokban.index')
            ->with('success', 'Data stok ban berhasil dihapus!');
    }

    public function dashboard()
    {
        // PERBAIKAN: Gunakan static methods dari model untuk konsistensi
        $totalStok = StokBan::getTotalStok();
        $totalNilaiStok = StokBan::getTotalNilaiStok();
        $totalItems = StokBan::count();
        $lowStockItems = StokBan::getLowStockCount();

        // PERBAIKAN: Gunakan method dari model
        $brandStats = StokBan::getBrandStats();
        $lowStockAlerts = StokBan::getLowStockAlerts();
        $recentStok = StokBan::getRecentStock();

        return view('stokban.dashboard', compact(
            'totalStok',
            'totalNilaiStok',
            'totalItems',
            'lowStockItems',
            'brandStats',
            'lowStockAlerts',
            'recentStok'
        ));
    }

    /**
     * Method untuk update stok via AJAX (jika diperlukan)
     */
    public function updateStok(Request $request, $id)
    {
        $request->validate([
            'stok' => 'required|integer|min:0'
        ]);

        $stokBan = StokBan::findOrFail($id);
        $stokBan->updateStok($request->stok);

        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil diupdate',
            'data' => [
                'stok' => $stokBan->stok,
                'status' => $stokBan->status,
                'total_nilai_stok' => $stokBan->formatted_total_nilai_stok
            ]
        ]);
    }

    /**
     * Method untuk cek stok via AJAX (jika diperlukan)
     */
    public function checkStock($id)
    {
        $stokBan = StokBan::findOrFail($id);

        return response()->json([
            'stok_available' => $stokBan->stok,
            'status' => $stokBan->status,
            'is_sufficient' => $stokBan->isStockSufficient(1), // Default cek untuk 1 item
            'minimum_stok' => $stokBan->minimum_stok
        ]);
    }

    /**
     * Method untuk export data (jika diperlukan)
     */
    public function export(Request $request)
    {
        $query = StokBan::query()
            ->search($request->search)
            ->byBrand($request->brand)
            ->byStatus($request->status)
            ->byType($request->type);

        $stokBans = $query->latest()->get();

        // Logic untuk export Excel/PDF bisa ditambahkan di sini
        // Menggunakan package seperti Maatwebsite/Laravel-Excel

        return response()->json([
            'total_data' => $stokBans->count(),
            'data' => $stokBans
        ]);
    }

    /**
     * Method untuk mendapatkan data brand (untuk select options)
     */
    public function getBrands()
    {
        $brands = StokBan::select('brand')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand');

        return response()->json($brands);
    }

    /**
     * Method untuk mendapatkan statistik cepat (untuk widget)
     */
    public function getQuickStats()
    {
        return response()->json([
            'total_stok' => StokBan::getTotalStok(),
            'total_nilai_stok' => StokBan::getTotalNilaiStok(),
            'total_items' => StokBan::count(),
            'low_stock_count' => StokBan::getLowStockCount(),
            'out_of_stock_count' => StokBan::getOutOfStockCount()
        ]);
    }
}