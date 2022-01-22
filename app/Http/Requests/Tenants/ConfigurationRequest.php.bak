<?php

namespace App\Http\Requests\Tenants;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ConfigurationRequest extends FormRequest
{
	function __construct() {
		$this->valid_configs = [
				'app.locale', 'app.timezone'
		];
	}
	
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
    	
    	    	
    	switch($this->method()) {
    		case 'GET':
    		case 'DELETE': {
    			return [];
    		}
    		case 'POST': {
    			return [
    				'key' => ['required', 'max:191',
    					'unique:configurations',
    					'regex:/\w+\.\w+(\.\w+)*/',
    					Rule::in($this->valid_configs)
    				],
    				'value' => 'required|max:191',
    			];
    		}
    		case 'PUT':
    		case 'PATCH': {
    			return [
    				'key' => ['required', 'max:191', 
    					'regex:/\w+\.\w+(\.\w+)*/',
    					Rule::unique('configurations')->ignore(request('configuration'), 'key'),
    					Rule::in($this->valid_configs)
    				],	
    				'value' => 'required|max:191',
    			];
    		}
    		default:
    			break;	
     	}
    }
    
    public function messages()
    {
    	return [
    			'key.in' => __('configuration.key_values') . implode (' | ', $this->valid_configs)
    	];
    }
}
