<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenants\Role;
use App\Http\Requests\Tenants\RoleRequest;
use App\Helpers\DateFormat;
use Illuminate\Support\Str;
use Ramsey\Uuid\Codec\OrderedTimeCodec;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

use Carbon\Carbon;


/**
 * REST API for Role
 *
 * @author frederic
 *        
 */
class RoleController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * HTML parameters:
	 *
	 * @param
	 *        	int per_page number of item per page
	 * @param
	 *        	int page to return
	 * @param
	 *        	string sort comma separated column names, use minus sign for reverse OrderedTimeCodec
	 *          ex: sort=allDay,-start
	 * @param
	 *        	string filter ?filter=sex:female,color:brown
	 *        	
	 * function parameters
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 *
	 * @return an object with pagination information and the collection in the data field
	 *        
	 */
	public function index(Request $request) {
		// Laravel default is 15
		$per_page = ($request->has ( 'page' )) ?  $request->get ('per_page') : 1000000;
				
		$query = Role::query();
		if ($request->has ('sort')) {
			
			$sorts = explode(',', $request->input ('sort'));
			
			foreach($sorts as $sortCol) {
				$sortDir = Str::startsWith( $sortCol, '-') ? 'desc' : 'asc';
				$sortCol = ltrim( $sortCol, '-');
				$query->orderBy($sortCol, $sortDir);
			}
		}
		
		if ($request->has ('filter')) {
			$filters = explode(',', $request->input ('filter'));
			
			foreach ($filters as $filter) {
				list($criteria, $value) = explode(':', $filter, 2);
				
				$operator_found = false;
				foreach (['<=', '>=', '<', '>'] as $op) {
					if (Str::startsWith($value, $op)) {
						$value = ltrim($value, $op);
						$query->where($criteria, $op, $value);
						$operator_found = true;
						break;
					}
				}
				// Todo check if it is not sensitive to Sql injection
				if (!$operator_found) $query->where($criteria, $value);
			}
		}
		
		return $query->paginate ($per_page);
	}

		
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 * 
	 * 
	 */
	public function store(RoleRequest $request) {
		$validatedData = $request->validated ();

		return Role::create ( $validatedData );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response

     * @SuppressWarnings("PMD.ShortVariable")
	 */
	public function show($id) {
		return Role::findOrFail ( $id );
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int $id
	 * @return \Illuminate\Http\Response

     * @SuppressWarnings("PMD.ShortVariable")
	 */
	public function update(RoleRequest $request, $id) {
		$validatedData = $request->validated ();

		return Role::whereId ( $id )->update ( $validatedData );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response

     * @SuppressWarnings("PMD.ShortVariable")
	 */
	public function destroy($id) {
		$role = Role::findOrFail ( $id );
		return $role->delete ();
	}

}
