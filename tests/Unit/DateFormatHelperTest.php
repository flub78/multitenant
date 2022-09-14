<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Helpers\DateFormat;
use App;
use Exception;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Str;

class DateFormatHelperTest extends TestCase {


	/**
	 * Date storage
	 *
	 * @return void
	 */
	public function testDatesInDifferentLanguagesCanBeStoredInDatabase() {
		App::setLocale ( 'fr' );
		$res = DateFormat::date_to_db ( "22/04/2018");
		$this->assertTrue ( $res == '2018-04-22', "basic french format " . $res );
		
		// Exception when a datetime is submited instead of a date
		$this->expectException(InvalidFormatException::class);
		$res = DateFormat::date_to_db ( "22/04/2018 01:00");

		App::setLocale ( 'en' );
		$res = DateFormat::date_to_db ( '04-22-2018');
		$this->assertTrue ( $res == "2018-04-22", "basic english format " . $res );
	}
	
	public function test_date_to_db_incorrect_input() {
		App::setLocale ( 'en' );
		$this->expectException(InvalidFormatException::class);
		$res = DateFormat::date_to_db ( '04-22-two-thousand-and-eighteen');
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
	
	public function test_datetime_to_db() {
		App::setLocale ( 'fr' );
		$res = DateFormat::datetime_to_db ( "2018-04-22 10:00", "Europe/Paris");
		$this->assertTrue ( $res == '2018-04-22 08:00:00', "basic french format no seconds " . $res );

		$res = DateFormat::datetime_to_db ( "2018-04-22 10:00:42", "Europe/Paris");
		$this->assertTrue ( $res == '2018-04-22 08:00:42', "basic french format with seconds " . $res );
	}
		
	public function test_datetime_to_db_incorrect_format() {
		App::setLocale ( 'fr' );
		
		$this->expectException(InvalidFormatException::class);
		$res = DateFormat::datetime_to_db ( "2018-04-22 0100:00:61", "Europe/Paris");
	}
		
	public function test_datetime_to_db_english() {
		App::setLocale ( 'en' );
		$res = DateFormat::datetime_to_db ( '2018-04-22 10:00', "UTC");
		$expected = "2018-04-22 10:00:00";
		$this->assertTrue ( $res == $expected, "basic english format " . $res  . " == " . $expected);
		
		$res = DateFormat::datetime_to_db ( '2018-04-22 00:00', "UTC");
		$this->assertTrue ( $res == "2018-04-22 00:00:00", "default time is 00:00 " . $res );

		$this->expectException(InvalidFormatException::class);
		$res = DateFormat::datetime_to_db ( '04-22-two-thousand', '', "UTC");		
	}
	
	public function test_local_datetime_en() {
	    App::setLocale ( 'en' );
	    $this->assertEquals ("", DateFormat::local_datetime(""));
	    
	    $date = "2022-09-05 09:00";
	    $this->assertTrue(Str::startsWith(DateFormat::local_datetime($date), 'local_datetime: Incorrect datetime:'));

	    $date = "2022-09-05 09:00:00";
	    $expected = "09-05-2022 09:00";
	    $this->assertEquals ($expected, DateFormat::local_datetime($date));
	}
	
	public function test_local_datetime_fr() {
	    App::setLocale ( 'fr' );
	    $this->assertEquals ("", DateFormat::local_datetime(""));
	    
	    $date = "2022-09-05 09:00";
	    $this->assertTrue(Str::startsWith(DateFormat::local_datetime($date), 'local_datetime: Incorrect datetime:'));
	    
	    
	    $date = "2022-09-25 09:00:00";
	    $expected = "25/09/2022 09:00";
	    $this->assertEquals ($expected, DateFormat::local_datetime($date));

	    $date = "2022-09-25 09:00:00";
	    $expected = "25/09/2022";
	    $this->assertEquals ($expected, DateFormat::local_datetime($date, true));
	}
}
