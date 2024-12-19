<?php

namespace App\Helpers;

use Exception;
use App\Helpers\HtmlHelper as HH;
use Illuminate\Support\Str;
use App\Helpers\BitsOperationsHelper as BO;

/**
 * Like the Html helper this class provides services to write more concise and elegant blade templates
 *
 * The HTML helpers support the generation of any HTML construct.
 * The Blade helper enforces project conventions, making the calls more concise
 * but limiting the diversity of generated HTML constructs.
 *
 * @author frederic
 * @reviewed 2022-04-02
 *        
 */
class BladeHelper {
	public static $indent = '    ';

	/**
	 * returns an HTML select from a list of [string, id]
	 *
	 * @param array $values
	 *        	list of name, id pairs
	 * @param boolean $with_null
	 * @param string $selected
	 * @param array $attrs
	 *        	HTML attributes
	 *        	
	 *        	<select name="vpmacid" id="vpmacid">
	 *        	<option value="F-CDYO">ASK13 - F-CDYO - (CJ)</option>
	 *        	<option value="F-CJRG">Ask21 - F-CJRG - (RG)</option>
	 *        	<option value="F-CERP">Asw20 - F-CERP - (UP)</option>
	 *        	<option value="F-CGKS">Asw20 - F-CGKS - (WE)</option>
	 *        	<option value="F-CFXR">xPégase - F-CFXR - (B114)</option>
	 *        	</select>
	 *        
	 * @SuppressWarnings("PMD.ShortVariable")
	 */
	static public function selector($id = "", $values = [], $selected = "", $attrs = []) {
		$attributes = array_merge($attrs, [
			"class" => "form-select",
			'name' => $id,
			'id' => $id
		]);
		return HH::selector($values, false, $selected, $attributes);
	}

	/**
	 * Same than selector with a null option
	 *
	 * @param string $id
	 * @param array $values
	 * @param string $selected
	 * @param array $attrs
	 * @return string
	 * 
	 * @SuppressWarnings("PMD.ShortVariable")
	 */
	static public function selector_with_null(string $id = "", array $values = [], string $selected = "", array $attrs = []) {
		$attributes = array_merge($attrs, [
			"class" => "form-select",
			'name' => $id,
			'id' => $id
		]);
		return HH::selector($values, true, $selected, $attributes);
	}

	/**
	 * Generate a select from a sequential or associative array
	 *
	 * @param unknown $id
	 * @param array $values
	 * @param boolean $with_null
	 * @param string $selected
	 * @param array $attrs
	 * @return string
	 * 
	 * @SuppressWarnings("PMD.ShortVariable")
	 */
	static public function select($id, $values = [], $with_null = false, $selected = "", $attrs = []) {
		return HH::select($values, $with_null, $selected, array_merge($attrs, [
			"class" => "form-select",
			'name' => $id,
			'id' => $id
		]));
	}

	/**
	 * Return a name to store an uploaded file
	 * There is a random part to avoid conflicts
	 * 
	 * @param unknown $name
	 * @param unknown $table_field
	 * @return string
	 */
	static public function upload_name($name, $filename) {
		$ext = pathinfo($name, PATHINFO_EXTENSION);
		return $filename . '_' . Str::random(8) . '.' . $ext;
	}

	/**
	 * Generate an a link to an image from a thumbnail
	 * @param unknown $route_name
	 * @param unknown $id
	 * @param unknown $field
	 * @param string $label
	 * @return string
	 * 
	 * @SuppressWarnings("PMD.ShortVariable")
	 */
	static public function picture($route_name, $id, $field, $filename, $label = "") {
		if (!$filename) return "";
		$url = route($route_name, ['id' => $id, 'field' => $field]);
		$img = ($label) ? $label : $field;
		$res = '<img src="'
			. $url . '" '
			. " alt=\"$img\" "
			. " title=\"$img\""
			. ' width="50" height="auto">';
		return "<a href=\"$url\">$res</a>";
	}

	/**
	 * Localized display of an enumerate value
	 * 
	 * @param String $table_field
	 * @param String $value
	 * @return string
	 */
	static public function enumerate($table_field, $value) {
		if (!$value) return "";
		return __($table_field . '.' . $value);
	}

	/**
	 * Display a bitfield
	 * 
	 * Basic implementation is to return a comma separated list of values.
	 * 
	 * @return string
	 *
	 * @SuppressWarnings("PMD.ShortVariable")
	 */
	static public function bitfield($table, $field, $bitfield, $element, $values = []) {

		$cnt = 0;
		$res = "";
		foreach ($values as $value) {
			if (BO::bit_at($bitfield, $cnt)) {
				if ($res) $res .= ", ";
				$lang_key = $element . '.'  . $field . '.' . $value;
				$val = (__($lang_key) == $lang_key) ? $value : __($lang_key);
				$res .= $val;
			}
			$cnt++;
		}
		return $res;
	}

	/**
	 * Input for bitfield
	 *
	 * @return string
	 *
	 * @SuppressWarnings("PMD.ShortVariable")
	 */
	static public function bitfield_input($table, $field, $bitfield, $element, $values) {
		$res = self::radioboxes($table, $field, $values, $bitfield, $element);
		return $res;
	}

	/**
	 * Generate a link to download an uploaded file
	 * @param unknown $route_name
	 * @param unknown $id
	 * @param unknown $field
	 * @param unknown $label
	 * @return string

	 * @SuppressWarnings("PMD.ShortVariable")
	 */
	static public function download($route_name, $id, $field, $filename, $label = "") {
		if (!$filename) return "";
		$url = route($route_name, ['id' => $id, 'field' => $field]);
		if (!$label) $label = $field;
		return "<a href=\"$url\">$label</a>";
	}

	/**
	 * Display float values according to locale
	 * 
	 * @param unknown $value
	 * @return string
	 */
	static public function float($value) {
		if (is_null($value)) return "";
		return number_format($value, 2);
	}

	/**
	 * Display currency values according to locale
	 * 
	 * @param unknown $value
	 * @return string
	 */
	static public function currency($value) {
		if ($value == "") return "";
		$symbol = Config::config('app.currency_symbol');
		return number_format($value, 2) . "&nbsp" . $symbol;
	}

	/**
	 * Generate a set of radioboxes for bitfield
	 *
	 * @param unknown $field
	 * @param array $values
	 * @param string $selected
	 * @param array $attrs
	 * @return string
	 *
	 * @SuppressWarnings("PMD.ShortVariable")
	 */
	static public function radioboxes($table, $field, $values = [], $bitfield = 0, $element) {

		$cnt = 0;
		$res = "<fieldset class=\"form-group d-sm-flex flex-wrap mt-5 mb-3 ms-2\">\n";
		foreach ($values as $value) {
			$res .= "             <div>\n";

			$res .= '               <label for="" class="form-label">';
			$lang_key = $element . '.'  . $field . '.' . $value;

			$res .= (__($lang_key) == $lang_key) ? $value : __($lang_key);
			$res .= "</label>\n";

			$res .= '               <input type="checkbox" name="' . $field . '_boxes[]" value="' . $cnt . '"';
			$res .= ' class="form-check-input me-3"';
			if (BO::bit_at($bitfield, $cnt)) $res .= ' checked="checked"';
			$res .= " />\n";
			$res .= "             </div>\n";

			$cnt++;
		}

		$res .= "</fieldset>\n";

		return $res;
	}
}
