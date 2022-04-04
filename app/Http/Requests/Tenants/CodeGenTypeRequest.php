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
    			    'phone' => ['nullable',
						'max:255',
						'unique:code_gen_types'],
    			    'description' => ['nullable'],
    			    'year_of_birth' => ['nullable',
						'numeric',
						'min:1901',
						'max:2099'],
    			    'weight' => ['nullable',
						'numeric',
						'min:3.0',
						'max:300.0'],
    			    'birthday' => ['nullable'],
    			    'tea_time' => ['nullable'],
    			    'takeoff_date' => ['nullable'],
    			    'takeoff_time' => ['nullable'],
    			    'price' => ['nullable',
						'numeric'],
    			    'big_price' => ['nullable',
						'numeric'],
    			    'qualifications_boxes' => ['nullable',
				],
    			    'color_name' => ['nullable',
						'max:255',
						Rule::in(["blue","red","green","white","black"])],
    			    'picture' => ['nullable',
						'max:2000'],
    			    'attachment' => ['nullable',
						'max:2000'],
   			];
    		}
    		case 'PUT':
    		case 'PATCH': {
    			return [
                    'name' => ['required',
						'max:255',
						Rule::unique('code_gen_types')->ignore(request('code_gen_type'))],
                    'phone' => ['nullable',
						'max:255',
						Rule::unique('code_gen_types')->ignore(request('code_gen_type'))],
                    'description' => ['nullable'],
                    'year_of_birth' => ['nullable',
						'numeric',
						'min:1901',
						'max:2099'],
                    'weight' => ['nullable',
						'numeric',
						'min:3.0',
						'max:300.0'],
                    'birthday' => ['nullable'],
                    'tea_time' => ['nullable'],
                    'takeoff_date' => ['nullable'],
                    'takeoff_time' => ['nullable'],
                    'price' => ['nullable',
						'numeric'],
                    'big_price' => ['nullable',
						'numeric'],
                    'qualifications' => ['nullable',
						'numeric'],
                    'color_name' => ['nullable',
						'max:255',
						Rule::in(["blue","red","green","white","black"])],
                    'picture' => ['nullable',
						'max:2000'],
                    'attachment' => ['nullable',
						'max:2000'],
    			];
    		}
    		default:
    			break;	
     	}
    }
}
