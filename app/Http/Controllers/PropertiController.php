<?php

namespace App\Http\Controllers;

use App\Models\Properti;
use Illuminate\Http\Request;

class PropertiController extends Controller
{
    public function updateType(Request $request, Properti $properti)
    {
        $request->validate([
            'tipe_properti' => 'required|string',
        ]);

        $properti->update([
            'tipe_properti' => $request->tipe_properti,
        ]);

        return back()->with('success', 'Tipe properti berhasil diperbarui.');
    }

    // --- Legacy Laporan Methods (Placeholder for now) ---
    public function laporanProject() { return view('modul.properti.laporan.project'); }
    public function laporanTahunan() { return view('modul.properti.laporan.tahunan'); }
    public function getProject($id) { return response()->json([]); }
    public function uploadLaporan(Request $request) { return response()->json(['success' => true]); }
    public function resetLaporan($id) { return response()->json(['success' => true]); }
    public function getTahunanByYear($year) { return response()->json([]); }
    public function deleteProject($id) { return response()->json(['success' => true]); }
    public function downloadZipTahunan($year) { return response()->json(['success' => true]); }
}
