<?php

namespace App\Http\Controllers;

use App\Models\Metadata;
use Illuminate\Http\Request;

class MetadataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$metadata = Metadata::all();
    	return view('metadata/index', compact('metadata'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	return view('metadata/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tenants\Metadata  $metadata
     * @return \Illuminate\Http\Response
     */
    public function show(Metadata $metadata)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tenants\Metadata  $metadata
     * @return \Illuminate\Http\Response
     */
    public function edit(Metadata $metadata)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tenants\Metadata  $metadata
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Metadata $metadata)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tenants\Metadata  $metadata
     * @return \Illuminate\Http\Response
     */
    public function destroy(Metadata $metadata)
    {
        //
    }
}
