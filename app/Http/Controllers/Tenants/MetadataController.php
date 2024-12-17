<?php

namespace App\Http\Controllers\Tenants;

use app\Http\Controllers\Controller;
use App\Models\Tenants\Metadata;
use App\Http\Requests\Tenants\MetadataRequest;
use Exception;
use App\Models\Tenants\TenantSchema;
use App\Helpers\HtmlHelper as HH;

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
		$attributes =  [
			"class" => "form-select big-select",
			'name' => "table_select",
			'id' => "table_select"
		];
		$tl = TenantSchema::tableList();
		$cl = TenantSchema::columnList();
		$selected = "";
		$table_select = HH::selector_from_list($tl, false, $selected, $attributes);

		$attributes =  [
			"class" => "form-select big-select",
			'name' => "field_select",
			'id' => "field_select"
		];
		$column_select = [];
		foreach ($cl as $table => $columns) {
			$column_select[$table] = HH::selector_from_list($columns, false, $selected, $attributes);
		}
		$initial_column_select = $column_select[$tl[0]];
		return view('tenants/metadata/create', compact('table_select', 'column_select', 'initial_column_select'));
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
			return redirect('/metadata')->with('success', __('general.creation_success', [
				'elt' => $metadata->full_name
			]));
		} catch (Exception $e) {
			// Should never happen as the validation should catch invalid data
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
	 *
	 * @SuppressWarnings("PMD.ShortVariable")
	 */
	public function update(MetadataRequest $request, $id) {
		$validatedData = $request->validated();

		$metadata = Metadata::find(['id' => $id])->first();
		$name = $metadata->full_name;
		$metadata->update($validatedData);

		return redirect('/metadata')
			->with('success', __('general.modification_success', ['elt' => $name]));
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
		return redirect('metadata')->with('success', __('general.deletion_success', [
			'elt' => $name
		]));
	}
}
