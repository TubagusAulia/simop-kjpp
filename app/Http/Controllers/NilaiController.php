<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Properti;
use App\Services\DocumentRequirementService;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    /**
     * Store or update a property valuation.
     * Only Karyawan can submit. Requires all documents and physical aspects verified.
     */
    public function store(Request $request, Properti $properti)
    {
        if (! auth()->user()->isKaryawan()) {
            abort(403, 'Hanya Karyawan yang dapat memberikan penilaian.');
        }

        // Check verification completeness
        $dokumenStatus = $this->checkDokumenStatus($properti);
        $fisikStatus = $this->checkFisikStatus($properti);

        if (! $dokumenStatus['complete'] || ! $fisikStatus['complete']) {
            $missing = [];
            if (! $dokumenStatus['complete']) {
                $missing[] = 'Dokumen belum lengkap: '.implode(', ', $dokumenStatus['missing']);
            }
            if (! $fisikStatus['complete']) {
                $missing[] = 'Verifikasi fisik belum lengkap: '.implode(', ', $fisikStatus['missing']);
            }

            return back()->with('warning', implode(' | ', $missing));
        }

        $request->validate([
            'nilai' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:1000',
        ]);

        Nilai::updateOrCreate(
            ['properti_id' => $properti->id],
            [
                'nilai' => $request->nilai,
                'catatan' => $request->catatan,
                'created_by' => auth()->id(),
            ]
        );

        return back()->with('success', 'Penilaian berhasil disimpan.');
    }

    /**
     * Delete a property valuation.
     * Only Karyawan can delete.
     */
    public function destroy(Nilai $nilai)
    {
        if (! auth()->user()->isKaryawan()) {
            abort(403, 'Hanya Karyawan yang dapat menghapus penilaian.');
        }

        $nilai->delete();

        return back()->with('success', 'Penilaian berhasil dihapus.');
    }

    /**
     * Check if all mandatory documents are verified.
     * Returns ['complete' => bool, 'missing' => array of labels]
     */
    private function checkDokumenStatus(Properti $properti): array
    {
        $typeReqs = DocumentRequirementService::getTypeRequirements($properti->tipe_properti);
        if (! $typeReqs) {
            return ['complete' => false, 'missing' => ['Tipe properti tidak dikenali']];
        }

        $globalReqs = DocumentRequirementService::getGlobalRequirements();
        $allMandatory = array_merge($globalReqs, $typeReqs['mandatory'] ?? []);

        $verifiedTypes = $properti->dokumens()
            ->where('status', 'terverifikasi')
            ->pluck('tipe_dokumen')
            ->toArray();

        $missing = [];
        foreach ($allMandatory as $key => $label) {
            if (! in_array($key, $verifiedTypes)) {
                // Check if uploaded but not yet verified
                $uploaded = $properti->dokumens()
                    ->where('tipe_dokumen', $key)
                    ->where('status', 'menunggu')
                    ->exists();
                if ($uploaded) {
                    $missing[] = "{$label} (menunggu verifikasi)";
                } else {
                    $missing[] = "{$label} (belum diunggah)";
                }
            }
        }

        return [
            'complete' => empty($missing),
            'missing' => $missing,
        ];
    }

    /**
     * Check if all mandatory physical aspects are verified.
     * Returns ['complete' => bool, 'missing' => array of item names]
     */
    private function checkFisikStatus(Properti $properti): array
    {
        $checklistWajib = $properti->checklistFisiks ?? collect();

        if ($checklistWajib->isEmpty()) {
            return ['complete' => false, 'missing' => ['Belum ada checklist fisik. Karyawan harus menambahkan checklist aspek fisik wajib.']];
        }

        $missing = [];
        foreach ($checklistWajib as $item) {
            $vStatus = $item->verificationStatus();
            if ($vStatus !== 'terverifikasi') {
                $statusLabel = $vStatus === 'belum' ? 'belum diisi' : ($vStatus === 'menunggu' ? 'menunggu verifikasi' : 'ditolak');
                $missing[] = "{$item->nama_item} ({$statusLabel})";
            }
        }

        return [
            'complete' => empty($missing),
            'missing' => $missing,
        ];
    }
}
