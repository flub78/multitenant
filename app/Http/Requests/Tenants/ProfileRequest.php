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
 * A Request to validate profile
 * 
 * @author frederic
 *
 */
 class ProfileRequest extends FormRequest {

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
    			    'first_name' => ['required',
						'max:128'],
    			    'last_name' => ['required',
						'max:128'],
    			    'birthday' => ['nullable'],
    			    'user_id' => ['nullable',
						'numeric',
						'exists:users,id'],
   			];
    		}
    		case 'PUT':
    		case 'PATCH': {
    			return [
                    'first_name' => ['required',
						'max:128'],
                    'last_name' => ['required',
						'max:128'],
                    'birthday' => ['nullable'],
                    'user_id' => ['nullable',
						'numeric',
						'exists:users,id'],
    			];
    		}
    		default:
    			break;	
     	}
    }
}
