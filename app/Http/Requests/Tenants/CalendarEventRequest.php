<?php

namespace App\Http\Requests\Tenants;

use Illuminate\Foundation\Http\FormRequest;

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
							'start' => 'required|date_format:' . __ ( 'general.date_format' ) . '',
							'start_time' => 'nullable|regex:/\d{1,2}\:\d{2}/',
							'end' => 'nullable|date_format:' . __ ( 'general.date_format' ) . '',
							'end_time' => 'nullable|regex:/\d{1,2}\:\d{2}/',
							'allDay' => '',
							'backgroundColor' => 'nullable|max:16',
							'textColor' => ''
					];
				}
			case 'PUT' :
			case 'PATCH' :
				{
					return [ 
							'title' => 'required|max:191',
							'description' => 'max:191',
							'start' => 'required|date_format:' . __ ( 'general.date_format' ) . '',
							'start_time' => 'nullable|regex:/\d{1,2}\:\d{2}/',
							'end' => 'nullable|date_format:' . __ ( 'general.date_format' ) . '',
							'end_time' => 'nullable|regex:/\d{1,2}\:\d{2}/',
							'allDay' => '',
							'backgroundColor' => 'nullable|max:16',
							'textColor' => ''
					];
				}
			default :
				break;
		}
	}

}
