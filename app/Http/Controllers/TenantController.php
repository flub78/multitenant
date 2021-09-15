<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stancl\Tenancy\Database\Models\Domain;
use App\Models\Tenant;
use App\Helpers\DirHelper;
use App\Helpers\TenantHelper;
use Illuminate\Validation\Rule;


class TenantController extends Controller
{
	// TODO create a request for tenants
	protected $create_rules = [
			'id' => [
					'required',
					'string',
					'max:255',
					'unique:tenants'
			],

			'email' => [
					'string', 'nullable',
					'email',
					'max:255',
					'unique:tenants'
			],
			'domain' => [
					'required',
					'string',
					'min:4'
			],
			'db_name' => [
					'string', 'nullable',
					'max:255',
					'unique:tenants'
			],
	];
	
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$tenants = Tenant::all();
    	
    	return view ( 'tenants_management.index', compact ( 'tenants' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	return view ( 'tenants_management.create' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$validatedData = $request->validate ( $this->create_rules );
    	
    	$tenant_id =  $validatedData ['id'];
    	        	
    	// $tenant = Tenant::create(['id' => $validatedData['id'], 'email' => $validatedData['email']]);
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
    	return view ( 'tenants_management.edit' )->with ( compact ( 'tenant' ) );
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
    	$db = request('db_name');
    	$edit_rules = [
    			'id' => ['required', 'string', 'max:255', Rule::unique('tenants')->ignore($id)],
    			
    			'email' => [
    					'string', 'nullable',
    					'email',
    					'max:255', Rule::unique('tenants')->ignore($id)
    			],
    			'domain' => [
    					'required',
    					'string',
    					'min:4'
    			],
    			'db_name' => [
    					'string', 'nullable',
    					'max:255', Rule::unique('tenants')->ignore($id)
    			],
    	];
    	
    	$validatedData = $request->validate ( $edit_rules );
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
     */
    public function destroy($id)
    {
    	$tenant = Tenant::findOrFail ( $id );
    	$id = $tenant->id;
    	$tenant->delete ();
    	
    	// delete tenant storage
    	$storage = TenantHelper::storage_dirpath($id);
		DirHelper::rrmdir($storage);
		
    	return redirect ( '/tenants' )->with ( 'success', "Tenant $id has been deleted" );
    }
}
