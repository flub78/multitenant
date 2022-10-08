<?php

namespace tests\Unit;

use Tests\TenantTestCase;
use App\Helpers\Config;
use App;

use App\Models\Tenants\CalendarEvent;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\Exceptions\InvalidFormatException;


/**
 * Experiment on Carbon library
 * 
 * https://carbon.nesbot.com/docs/
 * 
 * @author frederic
 *
 */
class CarbonTest extends TenantTestCase

{
    
    /**
     * Not to test the carbon library, but to learn how to use it
     * https://carbon.nesbot.com/docs/
     */
    public function test_carbon() {
    	
    	$date_regexp = '/(\d{4})\-(\d{2})\-(\d{2})\s(\d{2})\:(\d{2})\:(\d{2})/i';      // YYYY-DD-MM hh:mm:ss
    	
    	// By default timezone = UTC and format is Y-m-d h:i:s
    	$mutable = Carbon::now();
    	$mutable->add(1, 'day');
        // echo "tomorrow = " . $mutable->toDateString(); 
    	// $yesterday = Carbon::yesterday();
    	// echo "yesterday = " . $yesterday->toDateString(); 
    	$this->assertEquals(0, $mutable->utcOffset());
    	$this->assertMatchesRegularExpression($date_regexp, $mutable);
    	
    	$this->assertEquals($mutable, $mutable->toDateTimeString(), "Carbon date default value == toDateTimeString");		
    	
    	// today, tomorrow, yesterday are at 00:00:00.000000+0000
    	$this->assertEquals(Carbon::today()->add(1, 'day'), Carbon::tomorrow());
    	
    	$this->assertNotNull($mutable);
    	
    	Config::set('app.timezone', 'Europe/Paris');
    	$tz = Config::config('app.timezone');
    	    	
    	// now in local timezone
    	$date = Carbon::now($tz);
    	$this->assertMatchesRegularExpression($date_regexp, $date, 'By default TZ is not displayed in Carbon date');
    	$this->assertEquals($tz, $date->tzName, "TimeZone is contained in the object");
    	// echo "date = $date\n";		date = 2021-06-30 18:50:01
		
    	// by default now is in UTC
    	$date = Carbon::now();
    	$this->assertEquals(0, Carbon::now()->utcOffset());
 
    	// In summer Paris is 2 hours ahead of UTC
    	$this->assertTrue(Carbon::now($tz)->utcOffset() >= 60);		// True east of UTC    	
    	
    	$this->assertEquals('9999-12-31 23:59:59', Carbon::maxValue());
    	$this->assertEquals('0001-01-01 00:00:00', Carbon::minValue());
    	
    	// Create a carbon date from string with no time (by default the time is set to current time)
    	$seed_date = '1975-05-21';
    	$a_long_time_ago = Carbon::createFromFormat('Y-m-d', $seed_date, $tz);		// 1975-05-21 20:16:3110
    	$this->assertEquals($tz, $a_long_time_ago->tzName, "TimeZone is contained in the object");
	   	$this->assertEquals(strlen($a_long_time_ago), strlen($seed_date) + 9, 'carbon date contains time');
	   	// echo("--> " . $a_long_time_ago . "\n");
	   	
	   	// Create a carbon datetime from string
	   	$another_long_time_ago = Carbon::createFromFormat('Y-m-d H:i:s',  '1975-05-21 12:34:56');
	   	// echo("--> " . $another_long_time_ago . "\n");
	   	$this->assertEquals($another_long_time_ago, '1975-05-21 12:34:56');
	   	
	   	// Create a carbon datetime from string with no seconds
	   	// when seconds are not specified they are set to 0
	   	$another_long_time_ago = Carbon::createFromFormat('Y-m-d H:i',  '1975-05-21 12:34');
	   	// echo("--> " . $another_long_time_ago . "\n");
	   	$this->assertEquals($another_long_time_ago, '1975-05-21 12:34:00');
	   	
	   	// Set Carbon current time for testing
	   	// -----------------------------------
	   	$knownDate = Carbon::create(2001, 5, 21, 12, 0, 0, $tz); 
	   	Carbon::setTestNow($knownDate);    							
	   	$now = Carbon::now();
	   	// echo "tz = $knownDate $tz\n";
	   	
	   	// getters
	   	$this->assertEquals("2001-05-21 10:00:00", $now->format('Y-m-d h:i:s'));
	   	$this->assertEquals("2001", $now->year);
	   	$this->assertEquals("05", $now->month);
	   	$this->assertEquals("21", $now->day);
	   	$this->assertEquals("10", $now->hour);
	   	$this->assertEquals("00", $now->minute);
	   	$this->assertEquals("00", $now->second);
	   	$this->assertEquals("lundi", $now->locale('fr')->dayName);
	   	$this->assertEquals("mai", $now->locale('fr')->monthName);
	   	
	   	$this->assertTrue(Carbon::now()->utc);                         // weird but time zone has not been taken into account
	   	$this->assertEquals("UTC", Carbon::now()->timezone);
	   	$this->assertEquals(Carbon::now()->tz, Carbon::now()->timezone);
	   	
	   	// take a UTC time
	   	$utc = Carbon::now('UTC');
	   	$this->assertEquals('UTC', $utc->tz);

	   	// Conversion from UTC to local
	   	// ============================
	   	// create a local time from the first one
	   	// $local = $utc;		Wrong, would only copy the object reference
	    // $local = Carbon::create($utc->toDateTimeString());		// deep copy, but not efficient
	   	// $local = Carbon::createFromDate($utc);  // error
	   	$local = clone $utc;		// efficient deep copy
	   	
	   	$local->tz($tz);		// force another timezone, forcing a timezone adapt hour and maybe day
	   	
	   	$this->assertEquals('UTC', $utc->tz);		// one date is in UTC
	   	$this->assertEquals($tz, $local->tz);		// the other in Europe/Paris

	   	$this->assertEquals(10, $utc->hour);		// They do not have the same hour
	   	$this->assertEquals(12, $local->hour);

	   	$this->assertEquals(0, $utc->diffInSeconds($local));	// But they are the same time
	   	$this->assertTrue($utc->EqualTo($local)); 				// More efficient way to compare
	   	
	   	// New year eve
	   	$utc->year(1975)->month(12)->day(31)->hour(23)->minute(00)->second(0);
	   	// Paris is already in the next year
	   	$utc->tz($tz);
	   	$this->assertEquals(1976, $utc->year);
	   	$this->assertEquals(1, $utc->month);
	   	$this->assertEquals(1, $utc->day);
	   	$this->assertEquals(0, $utc->hour);
	   	
	   	
	   	// Equivalent setters exist
	   	$now->month = 4;
	   	$this->assertEquals("avril", $now->locale('fr')->monthName);
	   	
	   	// they can be chained
	   	$now->year(1975)->month(5)->day(21)->hour(22)->minute(32)->second(5);
	   	$this->assertEquals("1975-05-21 22:32:05", $now->toDateTimeString()); // toDateTimeString is mandatory, it is only implicitely called in string context

	   	$this->assertEquals("Wednesday 21st of May 1975 10:32:05 PM", $now->format('l jS \\of F Y h:i:s A'));
	   	
	   	// echo $now->serialize();
	   	$this->assertEquals('"1975-05-21T22:32:05.000000Z"', json_encode($now));
	   	
	   	// Back to real time
	   	Carbon::setTestNow(); 
	   	$this->assertNotEquals("2001-05-21 12:00:00", Carbon::now());
	   	
	   	$this->assertEquals('UTC', Carbon::now()->tzName);
	   	$first = Carbon::create(2012, 9, 5, 20, 26, 11);
	   	$second = Carbon::create(2012, 9, 5, 20, 26, 11, $tz);
	   	$second->add(2, 'hour');
	   	$this->assertEquals(0, $first->diffInSeconds($second));
	   	
	   	$second->subHour(2);
	   	$this->assertEquals("2 heures avant", $second->locale('fr')->diffForHumans($first));
	   	
	   	/*
	   	echo "\n";
	   	// These getters specifically return integers, ie intval()
	   	// var_dump(Carbon::SUNDAY);                          // int(0)
	   	// var_dump(Carbon::MONDAY);                          // int(1)
	   	// var_dump(Carbon::TUESDAY);                         // int(2)
	   	// var_dump(Carbon::WEDNESDAY);                       // int(3)
	   	// var_dump(Carbon::THURSDAY);                        // int(4)
	   	// var_dump(Carbon::FRIDAY);                          // int(5)
	   	// var_dump(Carbon::SATURDAY);                        // int(6)
	   	
	   	// var_dump(Carbon::YEARS_PER_CENTURY);               // int(100)
	   	// var_dump(Carbon::YEARS_PER_DECADE);                // int(10)
	   	// var_dump(Carbon::MONTHS_PER_YEAR);                 // int(12)
	   	// var_dump(Carbon::WEEKS_PER_YEAR);                  // int(52)
	   	// var_dump(Carbon::DAYS_PER_WEEK);                   // int(7)
	   	// var_dump(Carbon::HOURS_PER_DAY);                   // int(24)
	   	// var_dump(Carbon::MINUTES_PER_HOUR);                // int(60)
	   	// var_dump(Carbon::SECONDS_PER_MINUTE);              // int(60)
	   	*/   	
    }
    
    /**
     * Not to test the carbon library, but to learn how to use it
     * https://carbon.nesbot.com/docs/
     */
    public function test_carbon_interval() {
    	
    	$ci = CarbonInterval::create('P1Y2M3D');
    	$this->assertEquals("1 year 2 months 3 days", $ci->forHumans());
    	$this->assertFalse($ci->isEmpty());
    	
    	CarbonInterval::setLocale('fr');
    	$this->assertEquals("1 an 2 mois 3 jours", $ci->forHumans());
    	
    	$ci2 = new CarbonInterval('PT0S');
    	// echo "\ninterval = " . $ci2->forHumans() . "\n";
    	    	
    	$start  = new Carbon('2018-10-04 15:00:03');
    	$end    = new Carbon('2018-10-05 17:00:09');
    	// echo "diff = " . $start->diff($end)->format('%d %H:%I:%S') . "\n";
    	
    	// echo "diffInSeconds = " . $end->diffInSeconds($start) . "\n";
    	// echo "diffInMinutes = " . $end->diffInMinutes($start) . "\n";
    	
    }
    
    public function ttest_carbon_arithmethic() {
    	// '2021-06-30 12:00:00'
    	
    	$date = Carbon::now();
    	for ($i = 0; $i < 100; $i++) {
    		$date->add(2, 'hour');
    		// echo "\n" . $date->toDateTimeString();
    	}    	
    }
    
    function dateOf(string $dateTime) {
    	$carbon  = new Carbon($dateTime);
    	return $carbon->toDateString();
    }
    
    public function test_dateOf() {
    	$this->assertTrue(true);

    	$start = "2021-12-05T09:00:00";
    	$end = "2021-12-05T18:00:00";
    	
    	$this->assertEquals("2021-12-05", $this->dateOf($start));
    	$this->assertEquals("2021-12-05", $this->dateOf("2021-12-05"));
    	$this->assertEquals("2021-12-05", $this->dateOf($end));
    	
    }
    
    public function test_eng_to_local_date() {
    	// convert 2021-12-25 into 25/12/2021 
    	
    	$date = '1975-05-21';    	
    	
    	$this->assertEquals("05-21-1975", Carbon::createFromFormat('Y-m-d', $date)->format("m-d-Y"));
    	$this->assertEquals("21/05/1975", Carbon::createFromFormat('Y-m-d', $date)->format("d/m/Y"));
    }
    
    public function test_carbon_create() {
    	// $date = Carbon::parse('Fri Jan 07 2022 00:00:00 GMT 0100 (heure normale d’Europe centrale)');
    	$date = Carbon::parse('2022-01-07');
    	// echo "\ndate == " . $date->format('Y-m-d h:i:s A') ;
    	
    	$date = Carbon::parse('2021-12-30T09:00:00'); //  01:00');
    	// echo "\ndate == " . $date->format('Y-m-d h:i:s A') ;
    	
    	$date = Carbon::parse('31-07-2021 10:00');        // '07-31-2021 10:00' raises an exception
    	// echo "\ndate == " . $date->format('Y-m-d h:i:s A') ;
    	
    	$this->expectException(InvalidFormatException::class);
    	$date = Carbon::parse('zorglub');
    	
    }
}
