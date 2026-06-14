<?php

namespace App\Http\Controllers;

use App\Models\Properti;
use App\Models\Proyek;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PropertiController extends Controller
{
    public function updateType(Request $request, Properti $properti)
    {
        // Only Admin can change property type
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin yang dapat mengubah tipe properti.');
        }

        $request->validate([
            'tipe_properti' => 'required|string',
        ]);

        $properti->update([
            'tipe_properti' => $request->tipe_properti,
        ]);

        return back()->with('success', 'Tipe properti berhasil diperbarui.');
    }

    /**
     * Unified Laporan page — Proyek-style layout with switch toggle.
     */
    public function laporanProject()
    {
        // Laporan Proyek: only projects fully completed (phase 5: selesai, admin-approved)
        $laporanProyeks = Proyek::with(['properti.nilai.creator', 'clients'])
            ->where('current_phase', 'selesai')
            ->latest('updated_at')
            ->get();

        $proyekData = $laporanProyeks->map(function ($proyek) {
            $client = $proyek->clients->first();
            return [
                'id' => $proyek->id,
                'proyek' => $proyek,
                'client_name' => $client?->name ?? '-',
                'tanggal' => $proyek->updated_at,
                'nilai' => $proyek->properti?->nilai,
            ];
        });

        // Laporan Tahunan: yearly summary of fully completed projects
        $currentYear = now()->year;
        $laporanTahunan = [];
        for ($year = $currentYear; $year >= max($currentYear - 5, 2020); $year--) {
            $count = Proyek::where('current_phase', 'selesai')
                ->whereYear('updated_at', $year)
                ->count();
            if ($count > 0) {
                $laporanTahunan[] = [
                    'year' => $year,
                    'count' => $count,
                ];
            }
        }

        return view('modul.properti.laporan.index', compact('proyekData', 'laporanTahunan'));
    }

    /**
     * Show laporan detail page for a specific proyek.
     * Uses the same layout as proyek/show but with only Detail + Laporan tabs.
     */
    public function laporanProyekShow(Proyek $proyek)
    {
        if (!auth()->user()->isKaryawan() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $proyek->load([
            'creator', 'users',
            'properti.nilai.creator',
            'properti.dokumens.uploader',
            'properti.checklistFisiks.creator',
            'properti.checklistFisiks.aspekFisiks.creator',
        ]);

        $activeMenu = request('menu', 'detail');

        return view('modul.properti.laporan.show', compact('proyek', 'activeMenu'));
    }

    /**
     * Generate and download PDF laporan for a specific proyek.
     */
    public function downloadPdf(Proyek $proyek)
    {
        if (!auth()->user()->isKaryawan() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($proyek->current_phase !== 'selesai') {
            return back()->with('warning', 'Laporan hanya tersedia untuk proyek yang sudah selesai.');
        }

        $proyek->load([
            'properti.nilai.creator',
            'properti.nilai',
            'properti.dokumens.uploader',
            'properti.aspekFisiks.creator',
            'properti.checklistFisiks.creator',
            'clients',
            'creator',
        ]);

        $typeReqs = \App\Services\DocumentRequirementService::getTypeRequirements($proyek->properti->tipe_properti);

        // Build type labels for dokumen
        $globalReqs = \App\Services\DocumentRequirementService::getGlobalRequirements();
        $typeLabels = [];
        foreach ($globalReqs as $k => $v) { $typeLabels[$k] = $v; }
        if (isset($typeReqs['mandatory'])) {
            foreach ($typeReqs['mandatory'] as $k => $v) { $typeLabels[$k] = $v; }
        }
        $globalOptional = \App\Services\DocumentRequirementService::getGlobalOptionalRequirements();
        foreach ($globalOptional as $k => $v) { $typeLabels[$k] = $v; }
        if (isset($typeReqs['optional'])) {
            foreach ($typeReqs['optional'] as $k => $v) { $typeLabels[$k] = $v; }
        }

        $karyawans = $proyek->users->where('role', 'karyawan');
        $clients   = $proyek->users->where('role', 'client');
        $mitras    = $proyek->users->where('role', 'mitra');

        $pdf = Pdf::loadView('laporan.pdf', [
            'proyek'     => $proyek,
            'nilai'      => $proyek->properti?->nilai,
            'dokumens'   => $proyek->properti?->dokumens ?? collect(),
            'aspeks'     => $proyek->properti?->aspekFisiks ?? collect(),
            'typeLabels' => $typeLabels,
            'typeReqs'   => $typeReqs,
            'karyawans'  => $karyawans,
            'clients'    => $clients,
            'mitras'     => $mitras,
        ])->setPaper('a4', 'portrait');

        $filename = 'Laporan-' . str_replace('/', '-', $proyek->nama_proyek) . '.pdf';

        return $pdf->download($filename);
    }

    public function laporanTahunan()
    {
        // Redirect to unified view — Tahunan tab
        return redirect()->route('laporan.project');
    }

    public function getProject($id)
    {
        $proyek = Proyek::with(['properti.nilai', 'clients'])->findOrFail($id);
        return response()->json([
            'id' => $proyek->id,
            'nama_proyek' => $proyek->nama_proyek,
            'client' => $proyek->clients->first()?->name ?? '-',
            'nilai' => $proyek->properti?->nilai?->nilai,
            'catatan' => $proyek->properti?->nilai?->catatan,
        ]);
    }

    public function uploadLaporan(Request $request) { return response()->json(['success' => true]); }
    public function resetLaporan($id) { return response()->json(['success' => true]); }
    public function getTahunanByYear($year)
    {
        $proyeks = Proyek::whereHas('properti.nilai')
            ->whereYear('updated_at', $year)
            ->latest('updated_at')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'nama_proyek' => $p->nama_proyek,
                    'nilai' => $p->properti?->nilai?->nilai,
                ];
            });
        return response()->json($proyeks);
    }
    public function deleteProject($id) { return response()->json(['success' => true]); }

    /**
     * Generate and download a cumulative PDF report for all projects in a specific year.
     */
    public function downloadTahunanPdf($year)
    {
        if (!auth()->user()->isKaryawan() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $proyeks = Proyek::where('current_phase', 'selesai')
            ->whereYear('updated_at', $year)
            ->with([
                'properti.nilai.creator',
                'properti.nilai',
                'properti.dokumens.uploader',
                'properti.aspekFisiks.creator',
                'properti.checklistFisiks.creator',
                'clients',
                'users',
                'creator',
            ])
            ->latest('updated_at')
            ->get();

        if ($proyeks->isEmpty()) {
            return back()->with('warning', "Tidak ada laporan untuk tahun $year.");
        }

        // We'll reuse the logic for type labels if needed, but since it's cumulative,
        // we might just show basic info per project.
        
        $pdf = Pdf::loadView('laporan.pdf_tahunan', [
            'year'    => $year,
            'proyeks' => $proyeks,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("Laporan-Tahunan-$year.pdf");
    }

    public function downloadZipTahunan($year) { return response()->json(['success' => true]); }
}
