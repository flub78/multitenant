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
 * A Request to validate code_gen_type
 * 
 * @author frederic
 *
 */
 class CodeGenTypeRequest extends FormRequest {

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
						'unique:code_gen_types'],
    			    'phone' => ['max:255',
						'unique:code_gen_types'],
    			    'description' => [],
    			    'year_of_birth' => ['max:4'],
    			    'weight' => ['max:8'],
    			    'birthday' => [],
    			    'tea_time' => [],
    			    'takeoff_date' => [],
    			    'takeoff_time' => [],
    			    'price' => ['max:8'],
    			    'big_price' => ['max:11'],
    			    'qualifications' => ['max:20'],
    			    'picture' => ['max:2000'],
    			    'attachment' => ['max:2000'],
   			];
    		}
    		case 'PUT':
    		case 'PATCH': {
    			return [
                    'name' => ['required',
						'max:255',
						Rule::unique('code_gen_types')->ignore(request('code_gen_type'))],
                    'phone' => ['max:255',
						Rule::unique('code_gen_types')->ignore(request('code_gen_type'))],
                    'description' => [],
                    'year_of_birth' => ['max:4'],
                    'weight' => ['max:8'],
                    'birthday' => [],
                    'tea_time' => [],
                    'takeoff_date' => [],
                    'takeoff_time' => [],
                    'price' => ['max:8'],
                    'big_price' => ['max:11'],
                    'qualifications' => ['max:20'],
                    'picture' => ['max:2000'],
                    'attachment' => ['max:2000'],
    			];
    		}
    		default:
    			break;	
     	}
    }
}
