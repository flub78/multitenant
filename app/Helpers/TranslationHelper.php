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

			$res = $responseDecoded ['data'] ['translations'] [0] ['translatedText'];
		}

		if ($uppercase) {
			return ucfirst($res);
		}
		return $res;
	}

}