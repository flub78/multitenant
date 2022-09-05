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
 * A Request to validate configuration
 * 
 * @author frederic
 *
 */
 class ConfigurationRequest extends FormRequest {

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
        
        $authorised = [
            "app.locale",
            "app.timezone",
            "app.currency_symbol",
            "browser.locale"];
    	
    	switch($this->method()) {
    		case 'GET':
    		case 'DELETE': {
    			return [];
    		}
    		case 'POST': {
    			return [
    			    'key' => ['required',
						'max:255',
						'unique:configurations',
    			        Rule::in($authorised),
						'regex:/\w+\.\w+(\.\w+)*/'],
    			    'value' => ['required',
						'max:255'],
   			];
    		}
    		case 'PUT':
    		case 'PATCH': {
    			return [
                    'key' => ['required',
						'max:255',
						Rule::unique('configurations')->ignore(request('configuration'), 'key'),
                        Rule::in($authorised),
						'regex:/\w+\.\w+(\.\w+)*/'],
                    'value' => ['required',
						'max:255'],
    			];
    		}
    		default:
    			break;	
     	}
    }
}
