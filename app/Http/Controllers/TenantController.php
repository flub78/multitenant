<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;

class TenantController extends Controller
{
	protected $rules = [
			'id' => [
					'required',
					'string',
					'max:255'
			],
/*
			'email' => [
					'required',
					'string',
					'email',
					'max:255',
					'unique:tenants'
			],
*/			'domain' => [
					'required',
					'string',
					'min:4'
			]
	];
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
  
    	$tenants = Tenant::all ();
    	    	
    	return view ( 'tenants/index', compact ( 'tenants' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	return view ( 'tenants/create' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$validatedData = $request->validate ( $this->rules );
    	    	
    	// $tenant = Tenant::create(['id' => $validatedData['id'], 'email' => $validatedData['email']]);
    	$tenant = Tenant::create($validatedData);
    	$tenant->domains()->create(['domain' => $validatedData['domain']]);
    	    	
    	return redirect ( '/tenants' )->with ( 'success', 'Tenant ' . $validatedData ['id'] . ' has been created' );
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
    	$tenant = Tenant::findOrFail ( $id );
    	return view ( 'tenants/edit' )->with ( compact ( 'tenant' ) );
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
    	$validatedData = $request->validate ( $this->rules );
    	
    	$domain = $validatedData ['domain'];
    	unset($validatedData ['domain']);
    	
    	Tenant::whereId ( $id )->update ( $validatedData );
    	
    	$id = $validatedData ['id'];
    	return redirect ( '/tenants' )->with ( 'success', "Tenant $id has been updated" );
    	
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$tenant = Tenant::findOrFail ( $id );
    	$id = $tenant->id;
    	$tenant->delete ();
    	
    	return redirect ( '/tenants' )->with ( 'success', "Tenant $id has been deleted" );
    }
}
