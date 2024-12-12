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
 * A Request to validate personal_access_token
 * 
 * @author frederic
 *
 */
class PersonalAccessTokenRequest extends FormRequest {

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

		switch ($this->method()) {
			case 'GET':
			case 'DELETE': {
					return [];
				}
			case 'POST': {
					return [
						'tokenable_type' => [
							'max:255'
						],
						'tokenable_id' => [],
						'name' => [
							'required',
							'max:255',
							Rule::unique('personal_access_tokens')
						],
						'token' => [],
						'abilities' => ['nullable'],
						'last_used_at' => ['nullable'],
					];
				}
			case 'PUT':
			case 'PATCH': {
					return [
						'tokenable_type' => [
							'required',
							'max:255',
							Rule::unique('personal_access_tokens')->ignore(request('personal_access_token'))
						],
						'tokenable_id' => [
							'required',
							'numeric',
							Rule::unique('personal_access_tokens')->ignore(request('personal_access_token'))
						],
						'name' => [
							'required',
							'max:255'
						],
						'token' => [
							'required',
							'max:64',
							Rule::unique('personal_access_tokens')->ignore(request('personal_access_token'))
						],
						'abilities' => ['nullable'],
						'last_used_at' => ['nullable'],
					];
				}
			default:
				break;
		}
	}
}
