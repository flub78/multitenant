<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace App\Http\Requests\Tenants;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * A Request to validate role
 * 
 * @author frederic
 *
 */
 class RoleRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
    	
    	switch($this->method()) {
    		case 'GET':
    		case 'DELETE': {
    			return [];
    		}
    		case 'POST': {
    			return [
    			    'name' => ['required',
						'max:255',
						'unique:roles'],
    			    'description' => ['nullable',
						'max:255'],
   			];
    		}
    		case 'PUT':
    		case 'PATCH': {
    			return [
                    'name' => ['required',
						'max:255',
						Rule::unique('roles')->ignore(request('role'))],
                    'description' => ['nullable',
						'max:255'],
    			];
    		}
    		default:
    			break;	
     	}
    }
}
