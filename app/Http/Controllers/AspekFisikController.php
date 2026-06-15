<?php

namespace App\Http\Controllers;

use App\Models\AspekFisik;
use App\Models\Properti;
use Illuminate\Http\Request;

class AspekFisikController extends Controller
{
    /**
     * Store a new Aspek Fisik (verification submission for a checklist item).
     * Karyawan can set status directly; Mitra always creates as 'menunggu'.
     */
    public function store(Request $request, Properti $properti)
    {
        $request->validate([
            'checklist_fisik_id' => 'nullable|exists:checklist_fisik,id',
            'nama_aspek' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:wajib,opsional',
            'foto' => 'required|array|min:1',
            'foto.*' => 'image|mimes:jpg,jpeg,png|max:10240',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $fotoPaths = [];
        foreach ($request->file('foto') as $foto) {
            $fotoPaths[] = $foto->store('aspek-fisik', 'public');
        }

        $status = auth()->user()->isKaryawan() ? 'terverifikasi' : 'menunggu';

        $properti->aspekFisiks()->create([
            'checklist_fisik_id' => $request->checklist_fisik_id,
            'nama_aspek' => $request->nama_aspek,
            'deskripsi' => $request->deskripsi,
            'tipe' => $request->tipe,
            'foto_paths' => $fotoPaths,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => $status,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Aspek fisik berhasil ditambahkan.');
    }

    /**
     * Update an existing Aspek Fisik.
     * Mitra can only update their own aspects (no status change).
     * Karyawan can update any aspect.
     */
    public function update(Request $request, AspekFisik $aspekFisik)
    {
        $user = auth()->user();

        if ($user->isMitra() && $aspekFisik->created_by !== $user->id) {
            abort(403, 'Anda hanya dapat mengedit aspek yang Anda buat sendiri.');
        }

        $request->validate([
            'nama_aspek' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:wajib,opsional',
            'foto' => 'nullable|array|min:1',
            'foto.*' => 'image|mimes:jpg,jpeg,png|max:10240',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $data = [
            'nama_aspek' => $request->nama_aspek,
            'deskripsi' => $request->deskripsi,
            'tipe' => $request->tipe,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        if ($request->hasFile('foto')) {
            $fotoPaths = [];
            foreach ($request->file('foto') as $foto) {
                $fotoPaths[] = $foto->store('aspek-fisik', 'public');
            }
            $data['foto_paths'] = $fotoPaths;
        }

        $aspekFisik->update($data);

        return back()->with('success', 'Aspek fisik berhasil diperbarui.');
    }

    /**
     * Verify/reject an Aspek Fisik. Only Karyawan can do this.
     */
    public function verify(Request $request, AspekFisik $aspekFisik)
    {
        if (! auth()->user()->isKaryawan()) {
            abort(403, 'Hanya Karyawan yang dapat memverifikasi aspek fisik.');
        }

        $request->validate([
            'status' => 'required|in:terverifikasi,ditolak',
            'catatan' => 'nullable|string',
        ]);

        $aspekFisik->update([
            'status' => $request->status,
            'catatan' => $request->catatan,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        if ($request->expectsJson() || $request->header('X-Requested-With')) {
            return response()->json(['success' => true, 'message' => 'Status aspek fisik berhasil diperbarui.']);
        }

        return back()->with('success', 'Status aspek fisik berhasil diperbarui.');
    }

    /**
     * Delete an Aspek Fisik. Only Karyawan can delete.
     */
    public function destroy(AspekFisik $aspekFisik)
    {
        if (! auth()->user()->isKaryawan()) {
            abort(403, 'Hanya Karyawan yang dapat menghapus aspek fisik.');
        }

        $aspekFisik->delete();

        return back()->with('success', 'Aspek fisik berhasil dihapus.');
    }
}
