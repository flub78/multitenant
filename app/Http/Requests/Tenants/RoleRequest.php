<?php

namespace App\Http\Requests\Tenants;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
    				'name' => ['required', 'max:191'], 
    				'description' => 'required|max:191',
    			];
    		}
    		case 'PUT':
    		case 'PATCH': {
    			return [
    				'name' => ['required', 'max:191'],
    				'description' => 'required|max:191',
    			];
    		}
    		default:
    			break;	
     	}
    }
}
