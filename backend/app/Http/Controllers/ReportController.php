<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Facility;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Tampilkan form
    public function create()
    {
        $facilities = Facility::all(); // buat isi dropdown Location

        return view('reports.reports-page', compact('facilities'));
    }

    // Simpan report baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'category'    => 'required|string|max:255', // sesuaikan/hapus kalau belum ada field-nya
            'description' => 'required|string|max:3000', // ~500 kata
            'photo'       => 'nullable|image|max:2048',
        ]);

        $data = [
            'user_id'     => auth()->id(),
            'facility_id' => $validated['facility_id'],
            'category'    => $validated['category'],
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
}