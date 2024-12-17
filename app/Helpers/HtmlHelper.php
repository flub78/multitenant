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

	/**
	 * Generate a generic HTML marker
	 * 
	 * @param string $markup
	 * @param string $body
	 * @param array $attrs
	 * @return string
	 */
	static public function html(string $markup, string $body, $attrs = []) {
		$res = "";
		$res = '<' . $markup;
		foreach ($attrs as $key => $value) {
			$res .= " $key=\"$value\"";
		}
		$res .= '>' . $body . '</' . $markup . '>';
		return $res;
	}

	/**
	 * H1
	 * @return string
	 * @SuppressWarnings("PMD.ShortMethodName")
	 */
	static public function h1(string $body) {
		// https://stackoverflow.com/questions/151969/when-to-use-self-over-this
		// return self::html("H1", $body);
		return static::html("H1", $body);
	}

	/**
	 * p
	 * @return string
	 * @SuppressWarnings("PMD.ShortMethodName")
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
		$attrs = []
	) {
		$res = '<select';
		foreach ($attrs as $key => $value) {
			$res .= " $key=\"$value\"";
		}
		$res .= ">\n";
		if ($with_null) {
			$res .= '    <option value=""></option>' . "\n";
		}
		foreach ($values as $elt) {
			if (is_string($elt)) {
				// better to thrown an exception than return something that could be unnoticed
				throw new Exception("HtmlHelper.selector expect a list of associative arrays with id and name");
			}
			$res .= "    <option value=\"" . $elt['id'] . '"';
			if ($selected == $elt['id']) {
				$res .= ' selected="selected"';
			}
			// Todo replace name per image
			$res .= ">" . $elt['name'] . "</option>\n";
		}
		$res .= "</select>";
		return $res;
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
	static public function selector_from_list(
		$values = [],
		$with_null = false,
		$selected = "",
		$attrs = []
	) {
		$res = '<select';
		foreach ($attrs as $key => $value) {
			$res .= " $key=\"$value\"";
		}
		$res .= ">\n";
		if ($with_null) {
			$res .= '    <option value=""></option>' . "\n";
		}
		foreach ($values as $elt) {
			if (!is_string($elt)) {
				// better to thrown an exception than return something that could be unnoticed
				throw new Exception("HtmlHelper.selector_from_list expect a sequential list of values");
			}
			$res .= "    <option value=\"" . $elt . '"';
			if ($selected == $elt) {
				$res .= ' selected="selected"';
			}
			$res .= ">" . $elt . "</option>\n";
		}
		$res .= "</select>";
		return $res;
	}


	static function isAssoc($array) {
		return ($array !== array_values($array));
	}

	/**
	 * returns an HTML select from a PHP array, associative or sequential
	 *
	 * @param array $values
	 * @param boolean $with_null
	 * @param string $selected
	 * @param array $attrs HTML attributes
	 *
	 */
	static public function select(
		$values = [],
		$with_null = false,
		$selected = "",
		$attrs = []
	) {
		$tab = str_repeat("\t", 3);

		$res = '<select';
		foreach ($attrs as $key => $value) {
			$res .= " $key=\"$value\"";
		}
		$res .= ">\n";
		if ($with_null) {
			$res .= $tab . '    <option value=""></option>' . "\n";
		}
		if (self::isAssoc($values)) {
			foreach ($values as $key => $val) {

				$res .= $tab .  "    <option value=\"" . $key . '"';
				if ($selected == $key) {
					$res .= ' selected="selected"';
				}
				$res .= ">" . $val . "</option>\n";
			}
		} else {
			foreach ($values as $val) {

				$res .= $tab . "    <option value=\"" . $val . '"';
				if ($selected == $val) {
					$res .= ' selected="selected"';
				}
				$res .= ">" . $val . "</option>\n";
			}
		}

		$res .= "$tab</select>\n";
		return $res;
	}

	static public function input(
		$type,
		$class,
		$field,
		$attrs = []
	) {

		$attrs['type'] = $type;
		$attrs['class'] = $class;
		$attrs['name'] = $field;
		$attrs['value'] = '{{ old("' . $field . '") }}';

		$res = '<input';
		foreach ($attrs as $key => $value) {
			$res .= " $key=\"$value\"";
		}

		$res .= '/>';
		return $res;
	}
}
