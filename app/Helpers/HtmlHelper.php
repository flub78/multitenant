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
	
}