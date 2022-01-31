<?php

namespace Tests\Unit;


use Tests\TestCase;
use App\Helpers\TranslationHelper as Trans;

class TranslationHelperTest extends TestCase {

		
	public function testTranslate() {
		$this->assertEquals('', Trans::translate(''));
		$this->assertEquals('key', Trans::translate('key'));
		$this->assertEquals('Key', Trans::translate('key', "en", true));

		$this->assertEquals('Clé', Trans::translate('key', "fr", true));
		
		echo Trans::translate('The :attribute must be between :min and :max.', "fr", true);
		echo Trans::translate('The :attribute must be a string.', "fr", true);
		
	}
}
