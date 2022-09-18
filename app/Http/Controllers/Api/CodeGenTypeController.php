<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenants\CodeGenType;
use App\Http\Requests\Tenants\CodeGenTypeRequest;
use App\Helpers\DateFormat;
use Illuminate\Support\Str;
use Ramsey\Uuid\Codec\OrderedTimeCodec;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

use Carbon\Carbon;


/**
 * REST API for CodeGenType
 *
 * @author frederic
 *        
 */
class CodeGenTypeController extends Controller {

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
		// Laravel default is 15 elements per page
		$per_page = ($request->has ( 'page' )) ?  $request->get ('per_page') : 1000000;
				
		$query = CodeGenType::query();
		
		// the request has a sort parameter
		if ($request->has ('sort')) {
			
		    // the sort parameter can be a scalar or a comma separated list of fields
			$sorts = explode(',', $request->input ('sort'));
			
			foreach($sorts as $sortCol) {
				$sortDir = Str::startsWith( $sortCol, '-') ? 'desc' : 'asc';
				$sortCol = ltrim( $sortCol, '-');
				$query->orderBy($sortCol, $sortDir);
			}
		}
		
		// the request has a filter parameter
		if ($request->has ('filter')) {
		    
		    // filter parameter are comma separated list of filter criteria
			$filters = explode(',', $request->input ('filter'));
			
			foreach ($filters as $filter) {
			    
			    // a filter criteria is a field:value
				list($criteria, $value) = explode(':', $filter, 2);
				
				$operator_found = false;
				foreach (['<=', '>=', '<', '>', '<like>', '<>'] as $op) {
					if (Str::startsWith($value, $op)) {
						$value = ltrim($value, $op);
						if ($op == '<like>') $op = 'like';
						$query->where($criteria, $op, $value);
						$operator_found = true;
						break;
					}
				}
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
	public function store(CodeGenTypeRequest $request) {
		$validatedData = $request->validated ();
		
		return CodeGenType::create ( $validatedData );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response

     * @SuppressWarnings("PMD.ShortVariable")
	 */
	public function show($id) {
		return CodeGenType::findOrFail ( $id );
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int $id
	 * @return \Illuminate\Http\Response

     * @SuppressWarnings("PMD.ShortVariable")
	 */
	public function update(CodeGenTypeRequest $request, $id) {
		$validatedData = $request->validated ();
		$previous = CodeGenType::find($id);

        $this->store_datetime($validatedData, 'takeoff');
        $this->update_bitfield($validatedData, "qualifications", $request, "code_gen_type");
        $this->store_checkbox($validatedData, "black_and_white", $request, "code_gen_type");
        $this->update_picture($validatedData, "picture", $request, "code_gen_type", $previous);
        $this->update_file($validatedData, "attachment", $request, "code_gen_type", $previous);
        return CodeGenType::whereId ( $id )->update ( $validatedData );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response

     * @SuppressWarnings("PMD.ShortVariable")
	 */
	public function destroy($id) {
		$code_gen_type = CodeGenType::findOrFail ( $id );
		return $code_gen_type->delete ();
	}

}
