<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Office;
use App\Models\Division;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('offices.edit');
        $request->validate([
            'name' => 'required|unique:offices,name,' . $id,
            'code' => 'nullable|unique:offices,code,' . $id,
        ]);

        $office = Office::findOrFail($id);
        $office->update($request->all());

        return redirect()->route('offices.index')
            ->with('success', 'Office updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('offices.delete');
        $office = Office::findOrFail($id);
        $office->delete();

        return redirect()->route('offices.index')
            ->with('success', 'Office deleted successfully.');
    }

    public function getDivisions($id)
    {
        $divisions = Division::where('office_id', $id)->get();
        return response()->json($divisions);
    }
}
