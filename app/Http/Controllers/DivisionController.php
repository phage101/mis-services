<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\Office;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('divisions.list');
        $divisions = Division::with(['office'])->withCount('users')->get();
        $offices = Office::all();
        $kpis = [
            'total' => Division::count(),
            'with_users' => Division::has('users')->count(),
            'total_users' => \App\Models\User::whereNotNull('division_id')->count(),
        ];
        return view('divisions.index', compact('divisions', 'offices', 'kpis'));
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
        $this->authorize('divisions.create');
        $request->validate([
            'name' => 'required',
            'code' => 'nullable',
            'office_id' => 'required|exists:offices,id',
        ]);

        Division::create($request->all());

        return redirect()->route('divisions.index')
            ->with('success', 'Division created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Division $division)
    {
        return view('divisions.show', compact('division'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Division $division)
    {
        $offices = Office::all();
        return view('divisions.edit', compact('division', 'offices'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Division $division)
    {
        $this->authorize('divisions.edit');
        $request->validate([
            'name' => 'required',
            'code' => 'nullable',
            'office_id' => 'required|exists:offices,id',
        ]);

        $division->update($request->all());

        return redirect()->route('divisions.index')
            ->with('success', 'Division updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Division $division)
    {
        $this->authorize('divisions.delete');
        $division->delete();

        return redirect()->route('divisions.index')
            ->with('success', 'Division deleted successfully.');
    }
}
