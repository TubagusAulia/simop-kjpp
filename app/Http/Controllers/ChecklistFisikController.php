<?php

namespace App\Http\Controllers;

use App\Models\ChecklistFisik;
use App\Models\Properti;
use Illuminate\Http\Request;

class ChecklistFisikController extends Controller
{
    /**
     * Store a new checklist item. Only Karyawan can create.
     */
    public function store(Request $request, Properti $properti)
    {
        if (! auth()->user()->isKaryawan()) {
            abort(403, 'Hanya Karyawan yang dapat menambahkan checklist.');
        }

        $request->validate([
            'nama_item' => 'required|string|max:255',
            'tipe' => 'required|in:wajib,opsional',
        ]);

        $properti->checklistFisiks()->create([
            'nama_item' => $request->nama_item,
            'tipe' => $request->tipe,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Checklist berhasil ditambahkan.');
    }

    /**
     * Delete a checklist item. Only Karyawan can delete.
     */
    public function destroy(ChecklistFisik $checklistFisik)
    {
        if (! auth()->user()->isKaryawan()) {
            abort(403, 'Hanya Karyawan yang dapat menghapus checklist.');
        }

        $checklistFisik->delete();

        return back()->with('success', 'Checklist berhasil dihapus.');
    }
}
