<?php

namespace App\Http\Controllers;

use App\Imports\LaporanImport;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Laporan::with('user');

        if ($user->role === 'user') {
            $query->where('user_id', $user->id);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_perusahaan', 'like', '%' . $search . '%')
                  ->orWhere('posisi', 'like', '%' . $search . '%')
                  ->orWhere('status', 'like', '%' . $search . '%')
                  ->orWhere('keterangan', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        $laporans = $query->latest()->paginate(10);

        return view('laporan.index', compact('laporans'));
    }

    public function importForm()
    {
        return view('laporan.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new LaporanImport, $request->file('file'));
            
            return redirect()->route('laporan.index')
                ->with('success', 'Data laporan berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengimport data: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $laporan = Laporan::with('user')->findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'user' && $laporan->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('laporan.show', compact('laporan'));
    }

    public function edit($id)
    {
        $laporan = Laporan::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'user' && $laporan->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('laporan.edit', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'user' && $laporan->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'nama_perusahaan' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'status' => 'required|in:Applied,Interview,Rejected,Accepted',
            'keterangan' => 'nullable|string',
        ]);

        $laporan->update($request->all());

        return redirect()->route('laporan.index')
            ->with('success', 'Laporan berhasil diupdate!');
    }

    public function destroy($id)
    {
        $laporan = Laporan::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'user' && $laporan->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus laporan ini.');
        }

        $laporan->delete();

        return redirect()->route('laporan.index')
            ->with('success', 'Laporan berhasil dihapus!');
    }

    public function create()
    {
        return view('laporan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_perusahaan' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'status' => 'required|in:Applied,Interview,Rejected,Accepted',
            'keterangan' => 'nullable|string',
        ]);

        Laporan::create([
            'tanggal' => $request->tanggal,
            'nama_perusahaan' => $request->nama_perusahaan,
            'posisi' => $request->posisi,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('laporan.index')
            ->with('success', 'Laporan berhasil dibuat!');
    }
}