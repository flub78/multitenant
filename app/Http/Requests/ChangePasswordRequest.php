<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest {

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
				return [];
			}
			case 'PUT' :
			case 'PATCH' : {
				return [
				    'password' => [
						'required',
						'password'
				    ],
				    'new_password' => [
						'required',
						'string',
						'min:8',
						'confirmed',
				    	'different:password'
				    ]
				];
			}
			default :
				break;
		}
	}

}
