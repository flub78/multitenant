<?php

namespace App\Http\Requests\Tenants;

use Illuminate\Foundation\Http\FormRequest;

class CalendarEventRequest extends FormRequest
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
    				'name' => 'required|max:191',
    				'date' => 'required|date_format:' . __('general.date_format') . '',
    				'email' => 'required|unique:passports|email|max:191',
    				'number' => 'required|max:32',
    				'office' => 'required',
    				'filename' => '',
    			];
    		}
    		case 'PUT':
    		case 'PATCH': {
    			return [
    				'name' => 'required|max:191',
    				'date' => 'required|date_format:' . __('general.date_format') . '',
    				'email' => 'required|email|max:191',
    				'number' => 'required|max:11',
    				'office' => 'required',
    				'filename' => '',
    			];
    		}
    		default:
    			break;	
     	}
    }
}
