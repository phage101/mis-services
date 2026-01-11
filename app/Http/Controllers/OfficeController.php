<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Office;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('offices.list');
        $offices = Office::withCount(['divisions', 'users'])->get();
        $kpis = [
            'total' => Office::count(),
            'with_divisions' => Office::has('divisions')->count(),
            'total_users' => \App\Models\User::whereNotNull('office_id')->count(),
        ];
        return view('offices.index', compact('offices', 'kpis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('offices.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->authorize('offices.create');
        $request->validate([
            'name' => 'required|unique:offices,name',
            'code' => 'nullable|unique:offices,code',
        ]);

        Office::create($request->all());

        return redirect()->route('offices.index')
            ->with('success', 'Office created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Office  $office
     * @return \Illuminate\View\View
     */
    public function show(Office $office)
    {
        return view('offices.show', compact('office'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Office  $office
     * @return \Illuminate\View\View
     */
    public function edit(Office $office)
    {
        return view('offices.edit', compact('office'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Office  $office
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Office $office)
    {
        $this->authorize('offices.edit');
        $request->validate([
            'name' => 'required|unique:offices,name,' . $office->id,
            'code' => 'nullable|unique:offices,code,' . $office->id,
        ]);

        $office->update($request->all());

        return redirect()->route('offices.index')
            ->with('success', 'Office updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Office  $office
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Office $office)
    {
        $this->authorize('offices.delete');
        $office->delete();

        return redirect()->route('offices.index')
            ->with('success', 'Office deleted successfully.');
    }

    public function getDivisions($officeId)
    {
        $office = Office::where('id', $officeId)->orWhere('uuid', $officeId)->firstOrFail();
        return response()->json($office->divisions);
    }
}
