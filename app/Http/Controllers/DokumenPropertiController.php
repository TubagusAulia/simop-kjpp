<?php

namespace App\Http\Controllers;

use App\Models\Properti;
use App\Models\DokumenProperti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenPropertiController extends Controller
{
    public function store(Request $request, Properti $properti)
    {
        $request->validate([
            'tipe_dokumen' => 'required|string',
            'nama_dokumen' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // Max 10MB
        ]);

        $path = $request->file('file')->store('dokumen', 'public');

        DokumenProperti::create([
            'properti_id' => $properti->id,
            'uploaded_by' => auth()->id(),
            'tipe_dokumen' => $request->tipe_dokumen,
            'nama_dokumen' => $request->nama_dokumen,
            'file_path' => $path,
            'status' => 'menunggu',
        ]);

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }

    public function verifikasi(Request $request, DokumenProperti $dokumen)
    {
        $request->validate([
            'status' => 'required|in:terverifikasi,ditolak',
            'catatan' => 'nullable|string',
        ]);

        $dokumen->update([
            'status' => $request->status,
            'catatan' => $request->catatan,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Status dokumen berhasil diperbarui.');
    }

    public function destroy(DokumenProperti $dokumen)
    {
        // Only uploader can delete if still 'menunggu'
        if ($dokumen->uploaded_by !== auth()->id() || $dokumen->status !== 'menunggu') {
            abort(403, 'Anda tidak dapat menghapus dokumen ini.');
        }

        Storage::disk('public')->delete($dokumen->file_path);
        $dokumen->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}
