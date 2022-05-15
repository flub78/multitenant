<?php

namespace App\Http\Requests\Tenants;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Rules\IsTable;
use App\Rules\IsField;

class MetadataRequest extends FormRequest
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
    				'table' => ['required', 'max:191', new IsTable], 
    				'field' => ['required', 'max:191', new IsField(request('table')) ],
    				'subtype' => 'max:191',
    				'options' => 'nullable|max:191|json',
    				'foreign_key' => 'max:191',
    				'target_table' => ['max:191', new IsTable],
    				'target_field' => ['max:191', new IsField(request('table'))],
    			];
    		}
    		case 'PUT':
    		case 'PATCH': {
    			return [
    					'table' => ['required', 'max:191', new IsTable],
    					'field' => ['required', 'max:191', new IsField(request('table')) ],
    					'subtype' => 'max:191',
    					'options' => 'nullable|max:191|json',
    					'foreign_key' => 'max:191',
    					'target_table' => ['max:191', new IsTable],
    					'target_field' => ['max:191', new IsField(request('table'))],
    			];
    		}
    		default:
    			break;	
     	}
    }
}
