<?php

namespace App\Http\Controllers\Tenants;

use app\Http\Controllers\Controller;
use App\Models\Tenants\Metadata;
use Illuminate\Http\Request;
use App\Http\Requests\Tenants\MetadataRequest;
use Illuminate\Database\QueryException;
use Exception;

/**
 * Metadata controller
 *
 * GUI for metadata management
 *
 * @author frederic
 *        
 */
class MetadataController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$metadata = Metadata::all();
		return view('tenants/metadata/index', compact('metadata'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		return view('tenants/metadata/create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(MetadataRequest $request) {
		$validatedData = $request->validated(); // Only retrieve the data, the validation is done
		try {
			$metadata = Metadata::create($validatedData);
			$name = 'metadata';
			return redirect('/metadata')->with('success', __('general.creation_success', [ 'elt' => $name
			]));
		} catch (Exception $e) {
			$name = "metadata";
			$error = "Database error : " . $e->getMessage();
			return redirect('/metadata/create')->with('error', $error);
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param \App\Models\Tenants\Metadata $metadata
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Metadata $metadata) {
		return view('tenants/metadata/edit')->with(compact('metadata'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Models\Tenants\Metadata $metadata
	 * @return \Illuminate\Http\Response
	 */
	public function update(MetadataRequest $request, $id) {
		$validatedData = $request->validated();
		
		$metadata = Metadata::find([ 'id' => $id])->first();
		$name = $metadata->full_name;
		$metadata->update($validatedData);
		
		return redirect('/metadata')
			->with('success', __('general.modification_success', [ 'elt' => $name]	));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param \App\Models\Tenants\Metadata $metadata
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Metadata $metadata) {
		$name = $metadata->full_name;
		$metadata->delete();
		return redirect('metadata')->with('success', __('general.deletion_success', [ 'elt' => $name
		]));
	}

}
