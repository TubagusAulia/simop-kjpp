<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SurveyElement;
use App\Models\Project;

class SurveyController extends Controller
{
    public function getElements(Project $project)
    {
        $elements = SurveyElement::where('project_id', $project->id)->get();
        return response()->json(['success' => true, 'data' => $elements]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'file' => 'nullable|file|max:10240',
        ]);

        $element = SurveyElement::create([
            'project_id' => $request->project_id,
            'name' => $request->name,
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'pending',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('survey-elements', 'public');
            $element->update(['image_path' => $path]);
        }

        return response()->json(['success' => true, 'data' => $element]);
    }

    public function verifyElement($id)
    {
        $element = SurveyElement::findOrFail($id);
        $element->update(['status' => 'verified']);
        return response()->json(['success' => true]);
    }
}
