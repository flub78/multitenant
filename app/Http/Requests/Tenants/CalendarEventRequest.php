<?php

namespace App\Http\Requests\Tenants;

use Illuminate\Foundation\Http\FormRequest;

/**
 * A Request to validate calendar events
 * @author frederic
 * @reviewed 2022-01-08
 */
class CalendarEventRequest extends FormRequest {

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
			case 'DELETE' :
				{
					return [ ];
				}
			case 'POST' :
				{
				    return [
				        'title' => 'required|max:191',
				        'description' => 'max:191',
				        'start' => 'required|date',
				        'end' => ['nullable', 'date'],
				        'allDay' => '',
				        'backgroundColor' => 'starts_with:#',
				        'textColor' => 'starts_with:#'
				    ];
				    
				}
			case 'PUT' :
			case 'PATCH' :
				{
				    return [
				        'title' => 'required|max:191',
				        'description' => 'max:191',
				        'start' => 'required|date',
				        'end' => 'nullable|date',
				        'allDay' => '',
				        'backgroundColor' => 'nullable',
				        'textColor' => 'nullable'
				    ];
				}
			default :
				break;
		}
	}

}
