<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class TenantRequest extends FormRequest
{
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
    	switch ($this->method ()) {
    		case 'GET' :
    		case 'DELETE' : {
    			return [ ];
    		}
    		case 'POST' : {
    			return [
    					'id' => [
    							'required',
    							'string',
    							'max:255',
    							'unique:tenants'
    					],
    					
    					'email' => [
    							'string', 'nullable',
    							'email',
    							'max:255',
    							'unique:tenants'
    					],
    					'domain' => [
    							'required',
    							'string',
    							'min:4'
    					],
    					'db_name' => [
    							'string', 'nullable',
    							'max:255',
    							'unique:tenants'
    					],
    			];
    		}
    		case 'PUT' :
    		case 'PATCH' : {
    			return [
    					'id' => [
    							'required',
    							'string',
    							'max:255',
    							Rule::unique('tenants')->ignore(request('id'))
    					],
    					
    					'email' => [
    							'string', 'nullable',
    							'email',
    							'max:255',
    							Rule::unique('tenants')->ignore(request('id'))
    					],
    					'domain' => [
    							'required',
    							'string',
    							'min:4'
    					],
    					'db_name' => [
    							'string', 'nullable',
    							'max:255',
    							Rule::unique('tenants')->ignore(request('id'))
    					],
    			];
    		}
    		default :
    			break;
    	}    	
    }
}
