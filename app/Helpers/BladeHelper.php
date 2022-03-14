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
	static public function selector($id = "", $values = [ ], $selected = "", $attrs = [ ]) {
		$attributes = array_merge($attrs, [ "class" => "form-select",'name' => $id,'id' => $id
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
	static public function selector_with_null(string $id = "", array $values = [ ], string $selected = "", array $attrs = [ ]) {
		$attributes = array_merge($attrs, [ "class" => "form-select",'name' => $id,'id' => $id
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
	static public function select($id, $values = [ ], $with_null = false, $selected = "", $attrs = [ ]) {
		return HH::select($values, $with_null, $selected, array_merge($attrs, [ "class" => "form-select",'name' => $id,'id' => $id
		]));
	}

	/**
	 * Generate an a link to an image from a thumbnail
	 * @param unknown $img
	 * @param unknown $elt
	 * @return string
	 */
	static public function image($img, $elt) {
		if (!$img) return "";
		$url = route("$elt.image", $img);
		$res = '<img src="' 
				. $url . '" ' 
				. " alt=\"$img\" " 
				. " title=\"$img\"" 
				. ' width="50" height="auto">';
		return "<a href=\"$url\">$res</a>";
	}
	
	/**
	 * Generate a link to download an uploaded file
	 * @param unknown $filename
	 * @param unknown $elt
	 * @return string
	 */
	static public function download($filename, $elt) {
		if (!$filename) return "";
		$url = route("$elt.download", $filename);
		return "<a href=\"$url\">$filename</a>";
	}
}