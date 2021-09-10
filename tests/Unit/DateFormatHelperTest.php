<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Helpers\DateFormat;
use App;
use Exception;
use Carbon\Exceptions\InvalidFormatException;

class DateFormatHelperTest extends TestCase {


	/**
	 * Date storage
	 *
	 * @return void
	 */
	public function testDatesInDifferentLanguagesCanBeStoredInDatabase() {
		App::setLocale ( 'fr' );
		$res = DateFormat::date_to_db ( "22/04/2018", "Europe/Paris");
		$this->assertTrue ( $res == '2018-04-22', "basic french format " . $res );
		
		$this->expectException(InvalidFormatException::class);
		$res = DateFormat::date_to_db ( "22/04/2018 01:00", "Europe/Paris");

		App::setLocale ( 'en' );
		$res = DateFormat::date_to_db ( '04-22-2018', "UTC");
		$this->assertTrue ( $res == "2018-04-22", "basic english format " . $res );
	}
	
	public function test_date_to_db_incorrect_input() {
		App::setLocale ( 'en' );
		$this->expectException(InvalidFormatException::class);
		$res = DateFormat::date_to_db ( '04-22-two-thousand-and-eighteen', "UTC");
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
	
	public function test_datetime() {
		App::setLocale ( 'fr' );
		$res = DateFormat::datetime_to_db ( "22/04/2018", "10:00", "Europe/Paris");
		$this->assertTrue ( $res == '2018-04-22 08:00', "basic french format " . $res );
	}
		
	public function test_datetime_incorrect_format() {
		App::setLocale ( 'fr' );
		$this->expectException(InvalidFormatException::class);
		$res = DateFormat::datetime_to_db ( "22/04/2018", "01:00:61", "Europe/Paris");
	}
		
	public function test_datetime_english() {
		App::setLocale ( 'en' );
		$res = DateFormat::datetime_to_db ( '04-22-2018', '10:00', "UTC");
		$this->assertTrue ( $res == "2018-04-22 10:00", "basic english format " . $res );
		
		$res = DateFormat::datetime_to_db ( '04-22-2018', '', "UTC");
		$this->assertTrue ( $res == "2018-04-22 00:00", "default time is 00:00 " . $res );

		$this->expectException(InvalidFormatException::class);
		$res = DateFormat::datetime_to_db ( '04-22-two-thousand', '', "UTC");		
	}
}
