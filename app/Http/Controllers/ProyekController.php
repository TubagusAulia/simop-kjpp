<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Proyek;
use App\Models\AlokasiProyek;
use Illuminate\Http\Request;

class ProyekController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $proyeks = Proyek::with('creator')->latest()->get();
        } else {
            $proyeks = $user->proyeks()->with('creator')->latest()->get();
        }

        return view('proyek.index', compact('proyeks'));
    }

    public function show(Proyek $proyek)
    {
        $user = auth()->user();

        // Non-admin can only view projects they're assigned to
        $isAssigned = \App\Models\AlokasiProyek::where('proyek_id', $proyek->id)
            ->where('user_id', $user->id)
            ->exists();

        if (!$user->isAdmin() && !$isAssigned) {
            abort(403, "Anda tidak memiliki akses ke proyek #{$proyek->id}. Silakan hubungi Administrator.");
        }

        // Robustness: Ensure properti exists
        if (!$proyek->properti) {
            $proyek->properti()->create([
                'nama_properti' => $proyek->nama_proyek,
                'tipe_properti' => 'tanah_kosong' // Default fallback
            ]);
            $proyek->load('properti');
        }

        $proyek->load(['creator', 'users', 'properti.nilai.creator', 'properti.dokumens.uploader', 'properti.dokumens.verifier', 'properti.checklistFisiks.creator', 'properti.checklistFisiks.aspekFisiks.creator', 'properti.checklistFisiks.aspekFisiks.verifier']);

        // Determine active submenu (default: detail)
        $activeMenu = request('menu', 'detail');

        return view('proyek.show', compact('proyek', 'activeMenu'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $users = User::whereIn('role', ['karyawan', 'client', 'mitra'])->get();
        return view('proyek.create', compact('users'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'tipe_properti' => 'required|string',
            'deskripsi' => 'nullable|string',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
            'user_ids' => 'required|array|min:1',
            'kontrak_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $kontrakPath = $request->file('kontrak_file')->store('kontrak', 'public');

        $proyek = Proyek::create([
            'nama_proyek' => $request->nama_proyek,
            'deskripsi' => $request->deskripsi,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'status' => 'aktif',
            'kontrak_file' => $kontrakPath,
            'created_by' => auth()->id(),
        ]);

        // Update the automatically created properti with the selected type
        if ($proyek->properti) {
            $proyek->properti->update([
                'tipe_properti' => $request->tipe_properti,
            ]);
        }

        // Attach users (Klien, Karyawan, Mitra)
        $usersToAttach = array_filter($request->user_ids);
        foreach ($usersToAttach as $userId) {
            AlokasiProyek::create([
                'proyek_id' => $proyek->id,
                'user_id' => $userId,
                'allocated_by' => auth()->id(),
                'allocated_at' => now(),
            ]);
        }

        return redirect()->route('proyek.index')->with('success', 'Proyek berhasil dibuat.');
    }
}
