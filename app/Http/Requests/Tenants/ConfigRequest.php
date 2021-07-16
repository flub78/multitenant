<?php

namespace App\Http\Requests\Tenants;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ConfigRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
    	
    	$valid_configs = [
    	  'app.locale', 'app.timezone'
    	];
    	
    	switch($this->method()) {
    		case 'GET':
    		case 'DELETE': {
    			return [];
    		}
    		case 'POST': {
    			return [
    				'key' => ['required', 'max:191', 
    				'regex:/\w+\.\w+(\.\w+)*/',
    				Rule::in($valid_configs)
    				],
    				'value' => 'required|max:191',
    			];
    		}
    		case 'PUT':
    		case 'PATCH': {
    			return [
    				'key' => ['required', 'max:191', 
    				'regex:/\w+\.\w+(\.\w+)*/',
    				// Rule::in($valid_configs)
    				],	
    				'value' => 'required|max:191',
    			];
    		}
    		default:
    			break;	
     	}
    }
}
