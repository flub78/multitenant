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
 * A Request to validate motd
 * 
 * @author frederic
 *
 */
 class MotdRequest extends FormRequest {

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
    			    'title' => ['nullable',
						'max:128'],
    			    'message' => ['required',
						'max:1024'],
    			    'publication_date' => ['required'],
    			    'end_date' => ['nullable'],
   			];
    		}
    		case 'PUT':
    		case 'PATCH': {
    			return [
                    'title' => ['nullable',
						'max:128'],
                    'message' => ['required',
						'max:1024'],
                    'publication_date' => ['required'],
                    'end_date' => ['nullable'],
    			];
    		}
    		default:
    			break;	
     	}
    }
}
