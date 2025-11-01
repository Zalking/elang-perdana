<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LaporanImport;

class ImportController extends Controller
{
    public function index()
    {
        return view('laporan.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new LaporanImport, $request->file('file'));
        return redirect()->route('laporan.index')->with('success', 'Data berhasil diimport');
    }
}
