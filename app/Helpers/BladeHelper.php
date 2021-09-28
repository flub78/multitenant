<?php

namespace App\Helpers;

use Exception;
use App\Helpers\HtmlHelper as HH;


/**
 * Like the Html helper this class provides services to write more concise and elegant blade templates
 * 
 * The HTML helpers support the generation of any HTML construct.
 * The Blade helper enforces project conventions, making the calls more concise
 * but limiting the diversity of generated HTML constructs.
 * 
 * An alternative to this helper is the creation of blade directives, however
 * I have difficulties with blade directives with multiple structured parameters.
 * 
 * @author frederic
 *        
 */
class  BladeHelper {

	public static  $indent = '    ';
	
	/**
	 * @param String $for
	 * @param String $label
	 * @return string
	 */
	static public function label(String $for, String $label, $indent = 0) {
		// <label for="user_id">{{__('user_roles.user_id')}}</label>
		return str_repeat(self::$indent, $indent) 
			. '<label for=' . $for . '>' . $label . '</label>';
	}
	
	static public function input_create(String $type, String $name, $value) {
		return '<input type="' . $type . '" class="form-control" name="' 
				. $name . '" value="' . $value . '"/>';	
	}
	
	static public function text_create(String $name, $value) {
		return self::input_create("text", $name, $value);
	}
	
	static public function email_create(String $name, $value) {
		return self::input_create("email", $name, $value);
	}
	

	/**
	 * returns an HTML select from a list of [string, id]
	 * @param array $values list of name, id pairs
	 * @param boolean $with_null
	 * @param string $selected
	 * @param array $attrs HTML attributes
	 * 
	 * <select name="vpmacid" id="vpmacid">
			<option value="F-CDYO">ASK13 - F-CDYO - (CJ)</option>
			<option value="F-CJRG">Ask21 - F-CJRG - (RG)</option>
			<option value="F-CERP">Asw20 - F-CERP - (UP)</option>
			<option value="F-CGKS">Asw20 - F-CGKS - (WE)</option>
			<option value="F-CFXR">xPégase - F-CFXR - (B114)</option>
		</select>
	 */
	static public function selector(
			$id = "",
			$values = [], 
			$selected = "",
			$attrs = []) {
				$attributes = array_merge($attrs, ["class" => "form-select", 'name' => $id, 'id' => $id]);
		return HH::selector($values, false, $selected, $attributes);
	}
	
	/**
	 * @param string $id
	 * @param array $values
	 * @param string $selected
	 * @param array $attrs
	 * @return string
	 */
	static public function selector_with_null(
			string $id = "",
			array $values = [],
			string $selected = "",
			array $attrs = []) {
				$attributes = array_merge($attrs, ["class" => "form-select", 'name' => $id, 'id' => $id]);
				return HH::selector($values, true, $selected, $attributes);
	}
	
	/**
	 * @param bool $checked
	 * @return string
	 */
	static public function checkbox (bool $checked) {
		// <input type="checkbox" {{($user->admin) ? 'checked' : ''}} onclick="return false;" />
		$res = '<input type="checkbox" ';
		if ($checked) $res .= 'checked';
		$res .= ' onclick="return false;" />';
		return $res;
	}
	
}