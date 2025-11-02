<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PenjualanImport;
use App\Exports\PenjualanExport;

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
                $q->where('customer', 'like', '%' . $search . '%')
                  ->orWhere('brand', 'like', '%' . $search . '%')
                  ->orWhere('part', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter kategori
        if ($request->has('kategori') && $request->kategori != '' && $request->kategori != 'Semua Kategori') {
            $query->where('kategori', $request->kategori);
        }

        // Filter brand
        if ($request->has('brand') && $request->brand != '' && $request->brand != 'Semua Brand') {
            $query->where('brand', $request->brand);
        }

        // Clone query untuk menghitung total sebelum pagination
        $totalQuery = clone $query;
        
        $penjualans = $query->latest()->paginate(10);

        // Hitung totals dari query yang difilter
        $totalYTD = $totalQuery->sum('ytd');
        $totalMTD = $totalQuery->sum('mtd');
        $totalRecords = $totalQuery->count();

        // Get unique values for filters
        $kategoris = Penjualan::distinct()->pluck('kategori');
        $brands = Penjualan::distinct()->pluck('brand');
        $customers = Penjualan::distinct()->pluck('customer');

        return view('penjualan.index', compact(
            'penjualans', 
            'totalYTD', 
            'totalMTD',
            'totalRecords',
            'kategoris',
            'brands',
            'customers'
        ));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new PenjualanImport, $request->file('file'));
            return redirect()->route('penjualan.index')
                ->with('success', 'Data penjualan berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->route('penjualan.index')
                ->with('error', 'Error importing file: ' . $e->getMessage());
        }
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

        // Total calculations
        $totalYTD = $query->sum('ytd');
        $totalMTD = $query->sum('mtd');
        $totalRecords = $query->count();
        
        // Export vs Domestic
        $totalExport = Penjualan::when($user->role === 'user', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('kategori', 'Export')->sum('mtd');
        
        $totalDomestic = Penjualan::when($user->role === 'user', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('kategori', 'Domestic')->sum('mtd');

        // Top Brands
        $topBrands = Penjualan::when($user->role === 'user', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->selectRaw('brand, SUM(ytd) as total_ytd, SUM(mtd) as total_mtd')
        ->groupBy('brand')
        ->orderByDesc('total_ytd')
        ->limit(5)
        ->get();

        // Monthly data for chart
        $monthlyData = [];
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October'];
        
        foreach ($months as $month) {
            $monthlyData[$month] = Penjualan::when($user->role === 'user', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->sum(strtolower($month));
        }

        // Recent data
        $recentPenjualan = $query->with('user')->latest()->take(5)->get();

        return view('penjualan.dashboard', compact(
            'totalYTD', 
            'totalMTD',
            'totalRecords',
            'totalExport',
            'totalDomestic',
            'topBrands',
            'monthlyData',
            'recentPenjualan'
        ));
    }
    public function apiMonthlyData()
    {
        $user = Auth::user();
        
        $monthlyData = [];
        $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october'];
        $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt'];
        
        foreach ($months as $index => $month) {
            $monthlyData[$monthLabels[$index]] = Penjualan::when($user->role === 'user', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->sum($month);
        }
        
        return response()->json([
            'labels' => array_keys($monthlyData),
            'data' => array_values($monthlyData)
        ]);
    }

    public function apiBrandPerformance()
    {
        $user = Auth::user();
        
        $brands = Penjualan::when($user->role === 'user', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->selectRaw('brand, SUM(ytd) as total_ytd, SUM(mtd) as total_mtd')
        ->groupBy('brand')
        ->orderByDesc('total_ytd')
        ->limit(10)
        ->get();
        
        return response()->json($brands);
    }

    public function apiCustomerPerformance()
    {
        $user = Auth::user();
        
        $customers = Penjualan::when($user->role === 'user', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->selectRaw('customer, SUM(ytd) as total_ytd, SUM(mtd) as total_mtd')
        ->groupBy('customer')
        ->orderByDesc('total_ytd')
        ->limit(10)
        ->get();
        
        return response()->json($customers);
    }

    public function exportExcel()
    {
        return Excel::download(new PenjualanExport, 'penjualan-ban-' . date('Y-m-d') . '.xlsx');
    }

    public function downloadTemplate()
    {
        return response()->download(public_path('templates/template-penjualan-ban.xlsx'));
    }
}