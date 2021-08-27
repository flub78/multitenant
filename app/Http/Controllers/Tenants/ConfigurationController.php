<?php

namespace App\Http\Controllers\Tenants;

use App\Models\Tenants\Configuration;
use App\Http\Requests\Tenants\ConfigRequest;
use app\Http\Controllers\Controller;

/**
 * A controller for tenant configuration
 * 
 * The tenant configuration is a mechanism that manage configuration parameters per tenant.
 * For example every tenant could use his own language and be in his own timezone.
 * The basic configuration module can only return a global value for each parameter.
 * 
 * The current implementation uses a table in the tenant database. When a parameter
 * is not defined in the database, the mechanism fall back to the global configuration
 * parameter.
 * 
 * @author frederic
 *
 */
class ConfigurationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$configurations = Configuration::all ();
		return view ( 'tenants/configuration/index', compact ( 'configurations' ) );
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		return view ( 'tenants/configuration/create' );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(ConfigRequest $request) {
		$validatedData = $request->validated (); // Only retrieve the data, the validation is done
		Configuration::create ( $validatedData );
		
		return redirect ( '/configuration' )->with ( 'success',  __('general.creation_success', ['elt' => $validatedData ['key']]));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param \App\Models\Tenants\Configuration $configuration
	 * @return \Illuminate\Http\Response
	 */
	public function show(Configuration $configuration) {
		echo "ConfigurationController.show";
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param \App\Models\Tenants\Configuration $configuration
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Configuration $configuration) {
		return view ( 'tenants/configuration/edit' )->with ( compact ( 'configuration' ) );
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Models\Tenants\Configuration $configuration
	 * @return \Illuminate\Http\Response
	 */
	public function update(ConfigRequest $request, $id) {
		
		$validatedData = $request->validated ();
		
		Configuration::where ( ['key' => $id ])->update ( $validatedData );

		return redirect ( '/configuration' )->with ( 'success', __('general.modification_success', ['elt' => $validatedData ['key']]));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param \App\Models\Tenants\Configuration $configuration
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Configuration $configuration) {
		// var_dump($configuration); exit;
		$key = $configuration->key;
		$configuration->delete ();
		return redirect ( 'configuration' )->with ( 'success', __('general.deletion_success', ['elt' => $key]));
	}

}
