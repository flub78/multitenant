<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest {

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
					'name' => ['required', 'string', 'max:255'],
					'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
					'password' => ['required', 'string', 'min:8', 'confirmed']
				];
			}
			case 'PUT' :
			case 'PATCH' : {
				return [
				    'name' => [
						'required',
						'string',
						'max:255'
				    ],
				    'email' => [
						'required',
						'string',
						'email',
						'max:255',
				    	Rule::unique('users')->ignore(request('user')), 
				    ],
				    'password' => [
						'nullable',
						'string',
						'min:8',
						'confirmed'
				    ]
				];
			}
			default :
				break;
		}
	}

}
