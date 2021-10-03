<?php

namespace App\Http\Requests\Tenants;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserRoleRequest extends FormRequest
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
    			    'user_id' => ['required', 'max:20', 'exists:users,id'],
    				'role_id' => ['required', 'max:20', 'exists:roles,id'],
   			];
    		}
    		case 'PUT':
    		case 'PATCH': {
    			return [
    				'user_id' => ['required', 'max:20', 'exists:users,id'],
   					'role_id' => ['required', 'max:20', 'exists:roles,id'],
    			];
    		}
    		default:
    			break;	
     	}
    }
}
