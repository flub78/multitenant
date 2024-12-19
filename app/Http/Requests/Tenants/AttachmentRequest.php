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
 * A Request to validate attachment
 * 
 * @author frederic
 *
 */
 class AttachmentRequest extends FormRequest {

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
    			    'referenced_table' => ['nullable',
						'max:128'],
    			    'referenced_id' => ['nullable',
						'max:128'],
    			    'user_id' => ['nullable',
						'numeric'],
    			    'description' => ['required',
						'max:1024'],
    			    'file' => ['nullable',
						'max:2000'],
   			];
    		}
    		case 'PUT':
    		case 'PATCH': {
    			return [
                    'referenced_table' => ['nullable',
						'max:128'],
                    'referenced_id' => ['nullable',
						'max:128'],
                    'user_id' => ['nullable',
						'numeric'],
                    'description' => ['required',
						'max:1024'],
                    'file' => ['nullable',
						'max:2000'],
    			];
    		}
    		default:
    			break;	
     	}
    }
}
