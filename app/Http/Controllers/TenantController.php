<?php

namespace App\Http\Controllers;

use App\Http\Requests\TenantRequest;

use Stancl\Tenancy\Database\Models\Domain;
use App\Models\Tenant;
use App\Helpers\DirHelper;
use App\Helpers\TenantHelper;
use Illuminate\Validation\Rule;


class TenantController extends Controller
{
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
    	$tenants = Tenant::all();
    	
    	return view ( 'tenants_management.index', compact ( 'tenants' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
    	return view ( 'tenants_management.create' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\TenantRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TenantRequest $request) {
    	$validatedData = $request->validated ();
    	 	
    	$tenant_id =  $validatedData ['id'];
    	        	
    	$tenant = Tenant::create($validatedData);
    	    	    	
    	$tenant->domains()->create(['domain' => $validatedData['domain']]);
    	
    	// create local storage for the tenant
    	$storage = TenantHelper::storage_dirpath($tenant_id);
    	if (!is_dir($storage))
    		mkdir($storage, 0755, true);
    	
    	return redirect ( '/tenants' )->with ( 'success', "Tenant $tenant_id  has been created" );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @SuppressWarnings("PMD.ShortVariable")
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @SuppressWarnings("PMD.ShortVariable")
     */
    public function edit($id) {
    	$tenant = Tenant::findOrFail ( $id );
    	return view ( 'tenants_management.edit' )->with ( compact ( 'tenant' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\TenantRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @SuppressWarnings("PMD.ShortVariable")
     */
    public function update(TenantRequest $request, $id) {
    	$db = request('db_name');
    	
    	$validatedData = $request->validated ();
    	    	
    	$domain = $validatedData ['domain'];
    	unset($validatedData ['domain']);
    	
    	$tenant = Tenant::whereId ( $id );
    	$tenant->update ( $validatedData );
    	
    	// $tenant->domains()->update(['domain' => $domain]);
    	
    	$tenant_name = $validatedData ['id'];
    	return redirect ( '/tenants' )->with ( 'success', "Tenant $tenant_name has been updated" );    	
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @SuppressWarnings("PMD.ShortVariable")
     */
    public function destroy($id) {
    	$tenant = Tenant::findOrFail ( $id );
    	$id = $tenant->id;
    	$tenant->delete ();
    	
    	// delete tenant storage
    	$storage = TenantHelper::storage_dirpath($id);
		DirHelper::rrmdir($storage);
		
    	return redirect ( '/tenants' )->with ( 'success', "Tenant $id has been deleted" );
    }
}
