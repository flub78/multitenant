<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Helpers\DateFormat;
use App;
use Exception;


class DateHelperTest extends TestCase {

	/**
	 * Date display
	 *
	 * @return void
	 */
	public function testDatabaseDatesAreTranslatedAccordingToLocaleLanguages() {
		App::setLocale ( 'fr' );
		$res = DateFormat::db_to_date ( '2018-04-22' );
		$this->assertTrue ( $res == "22/04/2018", "basic french format " . $res );

		App::setLocale ( 'en' );
		$res = DateFormat::db_to_date ( '2018-04-22' );
		$this->assertTrue ( $res == "04-22-2018", "basic english format " . $res );
	}

	/**
	 * Date storage
	 *
	 * @return void
	 */
	public function testDatesInDifferentLanguagesCanBeStoredInDatabase() {
		App::setLocale ( 'fr' );
		$res = DateFormat::date_to_db ( "22/04/2018" );
		$this->assertTrue ( $res == '2018-04-22', "basic french format " . $res );

		App::setLocale ( 'en' );
		$res = DateFormat::date_to_db ( '04-22-2018' );
		$this->assertTrue ( $res == "2018-04-22", "basic english format " . $res );
	}

	/**
	 * Date storage
	 *
	 * @return void
	 */
	public function test_date_format_errors() {
		App::setLocale ( 'fr' );
		$this->expectException(Exception::class);
		DateFormat::date_to_db ( "xx/04/2018" );
	}

}
