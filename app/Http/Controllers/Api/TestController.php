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
 * REST API for testing
 *
 * @author frederic
 *        
 */
class TestController extends Controller {

	/**
	 * Returns some test information
	 *
     * function parameters
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 *
	 * @return a json object
	 *        
	 */
	public function index(Request $request) {

		$user = $request->user();
		// var_dump($user);
		
		// returns a json object
		return response()->json([
			'api' => 'v1',
			'environment' => app()->environment(),
			'base_url' => URL::to('/'),
			'api_url' => URL::to('/api/v1'),
			'name' =>$user->name,
			]);
	}



}
