<?php

namespace App\Http\Requests\Tenants;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MetadataRequest extends FormRequest
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
    	
    	switch($this->method()) {
    		case 'GET':
    		case 'DELETE': {
    			return [];
    		}
    		case 'POST': {
    			return [
    				'table' => ['required', 'max:191'], 
    				'field' => 'required|max:191',
    				'subtype' => 'max:191',
    				'options' => 'max:191',
    				'foreign_key' => 'max:191',
    				'target_table' => 'max:191',
    				'target_field' => 'max:191',
    			];
    		}
    		case 'PUT':
    		case 'PATCH': {
    			return [
    					'table' => ['required', 'max:191'],
    					'field' => 'required|max:191',
    					'subtype' => 'max:191',
    					'options' => 'max:191',
    					'foreign_key' => 'max:191',
    					'target_table' => 'max:191',
    					'target_field' => 'max:191',
    			];
    		}
    		default:
    			break;	
     	}
    }
}
