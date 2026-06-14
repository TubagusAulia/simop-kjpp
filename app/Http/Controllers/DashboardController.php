<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $currentYear = now()->year;

        // Get projects based on role (eager load properti + koleksi for pie chart)
        $query = Proyek::with(['properti.koleksiDokumen', 'properti.koleksiFisik']);
        if ($user->isAdmin()) {
            $proyeks = $query->latest()->get();
        } else {
            $proyeks = $user->proyeks()->with(['properti.koleksiDokumen', 'properti.koleksiFisik'])->latest()->get();
        }

        // --- Pie Chart: Unfinished projects by actual progress ---
        $unfinishedProyeks = $proyeks->where('status', '!=', 'selesai');

        $phaseDokumen = 0;
        $phaseFisik = 0;
        $phasePenilaian = 0;

        foreach ($unfinishedProyeks as $proyek) {
            $phase = $this->getActualPhase($proyek);
            if ($phase === 'dokumen') $phaseDokumen++;
            elseif ($phase === 'fisik') $phaseFisik++;
            elseif ($phase === 'nilai') $phasePenilaian++;
        }

        $pieData = [
            'labels' => ['Verifikasi Dokumen', 'Verifikasi Fisik', 'Penilaian'],
            'data' => [$phaseDokumen, $phaseFisik, $phasePenilaian],
        ];

        // --- Bar Chart: Completed projects per month (current year only) ---
        $completedProyeks = $proyeks->where('status', 'selesai');

        $monthlyCompleted = [];
        $monthLabels = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            // Only include months from current year
            if ($date->year == $currentYear) {
                $monthLabels[] = $date->format('M'); // "Jan", "Feb", etc.
                $count = $completedProyeks->filter(function ($proyek) use ($date) {
                    return $proyek->updated_at->format('Y-m') === $date->format('Y-m');
                })->count();
                $monthlyCompleted[] = $count;
            }
        }

        $barData = [
            'labels' => $monthLabels,
            'data' => $monthlyCompleted,
        ];

        // --- Project list: ordered by due date, with actual phase ---
        $projectsByPhase = $proyeks
            ->where('status', '!=', 'selesai')
            ->sortBy('due_date')
            ->values()
            ->map(function ($proyek) {
                $actualPhase = $this->getActualPhase($proyek);
                return [
                    'id' => $proyek->id,
                    'nama_proyek' => $proyek->nama_proyek,
                    'status' => $proyek->status,
                    'due_date' => $proyek->due_date,
                    'current_phase' => $actualPhase,
                ];
            });

        return view('dashboards.index', compact('proyeks', 'pieData', 'barData', 'projectsByPhase', 'currentYear'));
    }

    /**
     * Determine the active phase of a project based on actual progress,
     * not just the current_phase database column.
     *
     * - dokumen: documents not yet all verified (koleksi_dokumen not selesai)
     * - fisik:   all docs verified, but aspek fisik not all verified (koleksi_fisik not selesai)
     * - nilai:   all aspek verified, waiting for penilaian to be completed
     */
    private function getActualPhase(Proyek $proyek): string
    {
        $properti = $proyek->properti;
        if (!$properti) return 'dokumen';

        // Check koleksi_dokumen
        $koleksiDokumen = $properti->koleksiDokumen;
        if (!$koleksiDokumen || $koleksiDokumen->status !== 'selesai') {
            return 'dokumen';
        }

        // Check koleksi_fisik
        $koleksiFisik = $properti->koleksiFisik;
        if (!$koleksiFisik || $koleksiFisik->status !== 'selesai') {
            return 'fisik';
        }

        // All previous phases complete — project is in penilaian
        return 'nilai';
    }
}
