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
    			    'name' => ['required', 'max:255', 'unique:roles'],
    			    'description' => ['max:255'],
   			];
    		}
    		case 'PUT':
    		case 'PATCH': {
    			return [
                    'name' => ['required', 'max:255', Rule::unique('roles')->ignore(request('role'))],
                    'description' => ['max:255'],
    			];
    		}
    		default:
    			break;	
     	}
    }
}
