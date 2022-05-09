<?php

namespace App\Helpers;

use Exception;
use App\Helpers\HtmlHelper as HH;

/**
 * Automatic translation using the Google API
 * 
 * @author frederic
 *        
 */
class TranslationHelper {
	

	/**
	 * Translate one string
	 *
	 * @param String $str
	 *        	to translate
	 * @param String $to
	 *        	target language en | fr, ...
	 * @param boolean $uppercase
	 * @return String
     *
     * @SuppressWarnings("PMD.ShortVariable")
	 */
	static public function translate(String $str, String $to = "en", Bool $uppercase = false) {
		$res = $str;

		/*
		 * Google translation tutorial
		 * https://www.sitepoint.com/using-google-translate-api-php/
		 */
		$apiKey = env('TRANSLATE_API_KEY');

		if ($to != "en") {
			$text = $str;
			$url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($text) . '&source=en&target=' . $to;

			$handle = curl_init($url);
			curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($handle);
			$responseDecoded = json_decode($response, true);
			curl_close($handle);
		
			// if ($responseDecoded['error']) {
			if (array_key_exists("error", $responseDecoded)) {
				
				$code = (array_key_exists("code", $responseDecoded['error'])) ? $responseDecoded['error']['code'] : 0;
				$message = (array_key_exists("message", $responseDecoded['error'])) ? $responseDecoded['error']['message'] : "";
				$status = (array_key_exists("status", $responseDecoded['error'])) ?  $responseDecoded['error']['status'] : "";
				$msg = "Error $code, $status, $message\n";
				echo ($msg);
				echo "API Key = $apiKey\n";
				exit;
			}
			
			$res = $responseDecoded ['data'] ['translations'] [0] ['translatedText'];
		}

		if ($uppercase) {
			return ucfirst($res);
		}
		return $res;
	}
	
	static function isAssoc($array) {
		return ($array !== array_values($array));
	}
	
	
	/**
	 * Recursively translate an array
	 * 
	 * @param array $values
	 * @param String $to
     *
     * @SuppressWarnings("PMD.ShortVariable")
	 */
	static public function translate_array (array $values = [], String $to = "en") {

		$res = [];
		if (self::isAssoc($values)) {
			foreach ($values as $key => $val) {
				if (is_string($val)) {
					$res[$key] = self::translate($val, $to);
				} elseif (is_array($val)) {
					$res[$key] = self::translate_array($val, $to);
				} else {
					throw new Exception("unsupported value type " . var_export($val, true));
				}
			}
		} else {
			foreach ($values as $val) {
				if (is_string($val)) {
					$res[] = self::translate($val, $to);
				} elseif (is_array($val)) {
					$res[] = self::translate_array($val, $to);
				} else {
					throw new Exception("unsupported value type " . var_export($val, true));
				}
			}
		}
		return $res;
	}
	
	static public function flat_string_array(array $values = []) {
		if (self::isAssoc($values)) return false;
		if (!is_array($values)) return false;
		foreach ($values as $val) {
			if (!is_string($val)) {
				return false;
			}		
		}
		return true;
	}
	
	/**
	 * Pretty print an associative or sequential array
	 *
	 * @param array $values
	 * @param int $level
	 * @throws Exception
	 * @return string
	 */
	static public function pretty_print(array $values = [], int $level = 0) {
		$tab = str_repeat("\t", $level);
		
		if (self::flat_string_array($values)) {
			$sep = ", ";
			$res = '[';
		} else {
			$sep = ",\n$tab";
			$res = $tab . '[';
		}
		
		if ((count($values) > 1) && ! self::flat_string_array($values)) $res .= "\n";
		
		if (self::isAssoc($values)) {
			// associative array
			$total = count($values);
			$cnt = 0;
			
			foreach ($values as $key => $val) {
				if (is_string($val)) {
					$res .= "\t$tab\"$key\" => \"$val\"";
					
				} elseif (is_array($val)) {
					$res .= "\t$tab\"$key\" => " . self::pretty_print($val, $level + 1);
					
				} else {
					throw new Exception("unsupported value type " . var_export($val, true));
				}
				
				if ($cnt + 1 < $total) $res .= $sep;
				$cnt = $cnt + 1;
			}
			
		} elseif (is_array($values))  {
			// sequential array
			$total = count($values);
			$cnt = 0;
			foreach ($values as $val) {
				if (is_string($val)) {
					$res .= "\"$val\"";
					if ($cnt + 1 < $total) $res .= $sep;
					
				} elseif (is_array($val)) {
					$res .= self::pretty_print($val, $level + 1);
					
				} else {
					throw new Exception("unsupported value type " . var_export($val, true));
				}
				$cnt = $cnt + 1;
			}
		}
		
		if  (count($values) > 1 && self::isAssoc($values)) {
			$res .= "\n";
			$res .= $tab;
			$res .= ']';
		} else {
			$res .= ']';
		}
		
		return $res;
	}

}