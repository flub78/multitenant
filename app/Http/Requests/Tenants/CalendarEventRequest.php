<?php

namespace App\Http\Requests\Tenants;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\IsAfter;


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
	 * TODO: Checks that the end date is after the start date
	 * 'target_field' => ['max:191', new IsField(request('table'))],
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
							'end' => ['nullable', 'date_format:' . __ ( 'general.date_format' ), 
									new IsAfter(request('start'), request('start_time'), request('end_time')) ],
							'end_time' => 'nullable|regex:/\d{1,2}\:\d{2}/',
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
							'start' => 'required|date_format:' . __ ( 'general.date_format' ) . '',
							'start_time' => 'nullable|regex:/\d{1,2}\:\d{2}/',
							'end' => ['nullable', 'date_format:' . __ ( 'general.date_format' ),
									new IsAfter(request('start'), request('start_time'), request('end_time')) ],
							'end_time' => 'nullable|regex:/\d{1,2}\:\d{2}/',
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
