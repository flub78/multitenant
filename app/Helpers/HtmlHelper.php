<?php

namespace App\Helpers;

use Exception;


/**
 * Simple HTML string encapsulation
 *
 * @author frederic
 *        
 */
class  HtmlHelper {

	static public function html(string $markup, string $body, $attrs = [] ) {
		$res = "";
		$res = '<' . $markup . '>' . $body . '</' . $markup . '>';
		return $res;
	}
	
	/**
	 * H1
	 * @return string
	 */
	static public function h1(string $body) {
		// https://stackoverflow.com/questions/151969/when-to-use-self-over-this
		// return self::html("H1", $body);
		return static::html("H1", $body);
	}

	/**
	 * p
	 * @return string
	 */
	static public function p(string $body) {
		return static::html("p", $body);
	}
	
	/**
	 * returns an HTML select from a list of ['name' => 'xyz', 'id' => 'x']
	 * 
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
			$values = [], 
			$with_null = false,
			$selected = "",
			$attrs = []) {
		$res = '<select';
		foreach ($attrs as $key => $value) {
			$res .= " $key=\"$value\"";
		}
		$res .= ">\n";
		if ($with_null) {
			$res .= '    <option value=""></option>' . "\n";
		}
		foreach ($values as $elt) {
			$res .= "    <option value=\"" . $elt['id'] .'"';
			if ($selected == $elt['id']) {
				$res .= ' selected="selected"';
			}
			$res .= ">" . $elt['name'] . "</option>\n";
		}
		$res .= "</select>";
		return $res;
	}
}