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
            'deskripsi' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // Max 10MB
        ]);

        $path = $request->file('file')->store('dokumen', 'public');

        DokumenProperti::create([
            'properti_id' => $properti->id,
            'uploaded_by' => auth()->id(),
            'tipe_dokumen' => $request->tipe_dokumen,
            'nama_dokumen' => $request->nama_dokumen,
            'deskripsi' => $request->deskripsi,
            'file_path' => $path,
            'status' => 'menunggu',
        ]);

        if ($request->expectsJson() || $request->header('X-Requested-With')) {
            return response()->json(['success' => true, 'message' => 'Dokumen berhasil diunggah.']);
        }
        return back()->with('success', 'Dokumen berhasil diunggah.');
    }

    /**
     * Update document details. Client can edit their own documents (except status).
     * Uses multipart form data via fetch (FormData).
     */
    public function update(Request $request, DokumenProperti $dokumen)
    {
        $user = auth()->user();

        // Only the uploader (client) can update, and only if not yet verified by karyawan
        if ($dokumen->uploaded_by !== $user->id) {
            abort(403, 'Anda tidak dapat mengedit dokumen ini.');
        }

        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'tipe_dokumen' => 'required|string',
            'deskripsi' => 'nullable|string',
            'catatan' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $data = [
            'nama_dokumen' => $request->nama_dokumen,
            'tipe_dokumen' => $request->tipe_dokumen,
            'deskripsi' => $request->deskripsi,
            'catatan' => $request->catatan,
        ];

        if ($request->hasFile('file')) {
            // Delete old file
            Storage::disk('public')->delete($dokumen->file_path);
            $data['file_path'] = $request->file('file')->store('dokumen', 'public');
            // Reset status to menunggu when file changes
            $data['status'] = 'menunggu';
            $data['verified_by'] = null;
            $data['verified_at'] = null;
        }

        $dokumen->update($data);

        return response()->json(['success' => true, 'message' => 'Dokumen berhasil diperbarui.']);
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

        if ($request->expectsJson() || $request->header('X-Requested-With')) {
            return response()->json(['success' => true, 'message' => 'Status dokumen berhasil diperbarui.']);
        }
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
