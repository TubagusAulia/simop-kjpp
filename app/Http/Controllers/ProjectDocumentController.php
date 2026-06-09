<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectDocumentController extends Controller
{
    public function index()
    {
        return view('modul.properti.karyawan.dokumen');
    }

    public function download($document)
    {
        return response()->json(['success' => true]);
    }

    public function verify($document)
    {
        return response()->json(['success' => true]);
    }

    public function verifyProject($project)
    {
        return response()->json(['success' => true]);
    }

    public function verifyBatch(Request $request)
    {
        return response()->json(['success' => true]);
    }

    public function downloadAll($project)
    {
        return response()->json(['success' => true]);
    }
}
