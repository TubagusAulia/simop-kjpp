<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function create()
    {
        return view('modul.properti.client.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_project' => 'required|string|max:255',
            'contract_date' => 'required|date',
            'contact_person' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $project = Project::create([
            'nama_project' => $request->nama_project,
            'contract_date' => $request->contract_date,
            'contact_person' => $request->contact_person,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'client_id' => auth()->id(),
            'status' => 'pending',
        ]);

        return redirect()->route('properti.client')->with('success', 'Project berhasil ditambahkan.');
    }

    public function show(Project $project)
    {
        return view('modul.properti.client.index', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('modul.properti.client.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'nama_project' => 'required|string|max:255',
            'contract_date' => 'required|date',
            'contact_person' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $project->update($request->only(['nama_project', 'contract_date', 'contact_person', 'kategori', 'deskripsi']));

        return redirect()->route('properti.client')->with('success', 'Project berhasil diupdate.');
    }

    public function clientEdit(Project $project)
    {
        return view('modul.properti.client.edit', compact('project'));
    }

    public function clientUpdate(Request $request, Project $project)
    {
        return $this->update($request, $project);
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('properti.client')->with('success', 'Project berhasil dihapus.');
    }
}
