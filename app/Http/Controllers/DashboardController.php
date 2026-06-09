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

        // Get projects based on role
        if ($user->isAdmin()) {
            $proyeks = Proyek::latest()->get();
        } else {
            $proyeks = $user->proyeks()->latest()->get();
        }

        // --- Pie Chart: Unfinished projects by phase ---
        $unfinishedProyeks = $proyeks->where('status', '!=', 'selesai');

        $phaseDokumen = 0;
        $phaseFisik = 0;
        $phasePenilaian = 0;

        foreach ($unfinishedProyeks as $proyek) {
            $phase = $this->getCurrentPhase($proyek);
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

        // --- Project list: ordered by due date, with current phase ---
        $projectsByPhase = $proyeks
            ->where('status', '!=', 'selesai')
            ->sortBy('due_date')
            ->values()
            ->map(function ($proyek) {
                $currentPhase = $this->getCurrentPhase($proyek);
                return [
                    'id' => $proyek->id,
                    'nama_proyek' => $proyek->nama_proyek,
                    'status' => $proyek->status,
                    'due_date' => $proyek->due_date,
                    'current_phase' => $currentPhase,
                ];
            });

        return view('dashboards.index', compact('proyeks', 'pieData', 'barData', 'projectsByPhase', 'currentYear'));
    }

    private function getCurrentPhase(Proyek $proyek): string
    {
        return $proyek->current_phase ?? 'dimulai';
    }
}
