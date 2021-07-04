<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Helpers\DateFormat;
use App;
use Exception;


class DateFormatHelperTest extends TestCase {

	/**
	 * Date display
	 *
	 * @return void
	 */
	public function testDatabaseDatesAreTranslatedAccordingToLocaleLanguages() {
		App::setLocale ( 'fr' );
		$res = DateFormat::db_to_date ( '2018-04-22', "Europe/Paris");
		$this->assertTrue ( $res == "22/04/2018", "basic french format " . $res );

		App::setLocale ( 'en' );
		$res = DateFormat::db_to_date ( '2018-04-22', "UTC");
		$this->assertTrue ( $res == "04-22-2018", "basic english format " . $res );
	}

	/**
	 * Date storage
	 *
	 * @return void
	 */
	public function testDatesInDifferentLanguagesCanBeStoredInDatabase() {
		App::setLocale ( 'fr' );
		$res = DateFormat::date_to_db ( "22/04/2018", "Europe/Paris");
		$this->assertTrue ( $res == '2018-04-22', "basic french format " . $res );
		
		try {
			$res = DateFormat::date_to_db ( "22/04/2018 01:00", "Europe/Paris");
		} catch (Exception $e) {
			// echo "Exception: " . $e->getMessage() . "\n";
		}

		App::setLocale ( 'en' );
		$res = DateFormat::date_to_db ( '04-22-2018', "UTC");
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

	public function test_db_date_has_date() {
		$this->assertTrue (DateFormat::db_date_has_date("2018-04-22"));
		$this->assertTrue (DateFormat::db_date_has_date("2018-04-22 00:00:00"));
		$this->assertTrue (DateFormat::db_date_has_date("2018-04-22 12:34:56"));

		$this->assertFalse (DateFormat::db_date_has_date("12:34:56"));		
	}

	public function test_db_date_has_time() {
		$this->assertFalse (DateFormat::db_date_has_time("2018-04-22"));
		$this->assertTrue (DateFormat::db_date_has_time("2018-04-22 00:00:00"));
		$this->assertTrue (DateFormat::db_date_has_time("2018-04-22 12:34:56"));
		
		$this->assertFalse (DateFormat::db_date_has_time("12:34"));
	}
	
	public function test_datetime() {
		App::setLocale ( 'fr' );
		$res = DateFormat::datetime_to_db ( "22/04/2018", "10:00", "Europe/Paris");
		$this->assertTrue ( $res == '2018-04-22 08:00', "basic french format " . $res );
		
		try {
			$res = DateFormat::datetime_to_db ( "22/04/2018", "01:00:61", "Europe/Paris");
		} catch (Exception $e) {
			// echo "Exception: " . $e->getMessage() . "\n";
		}
		
		App::setLocale ( 'en' );
		$res = DateFormat::datetime_to_db ( '04-22-2018', '10:00', "UTC");
		$this->assertTrue ( $res == "2018-04-22 10:00", "basic english format " . $res );
		
	}
}
