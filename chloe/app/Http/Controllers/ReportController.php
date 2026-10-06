<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Facility;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function create()
    {
        $locations  = Facility::where('type', '!=', 'alat')->get();
        $equipments = Facility::where('type', 'alat')->get();

        return view('reports.reports-page', compact('locations', 'equipments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category'    => 'required|in:lokasi,peralatan',
            'facility_id' => 'required|exists:facilities,id',
            'description' => 'required|string|max:3000',
            'photo'       => 'nullable|image|max:2048',
        ]);

        $data = [
            'user_id'     => auth()->id(),
            'category'    => $validated['category'],
            'facility_id' => $validated['facility_id'],
            'description' => $validated['description'],
        ];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('reports', 'public');
        }

        Report::create($data);

        return redirect()
            ->route('reports.create')
            ->with('success', 'Report berhasil dikirim!');
    }

    public function history()
    {
        $reports = \App\Models\Report::where('user_id', auth()->id())
                    ->with('facility')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('reports.history', compact('reports'));
    }
}
