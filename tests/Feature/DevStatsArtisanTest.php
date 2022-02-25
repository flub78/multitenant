<?php

/**
 * Test cases:
 *
 * Checks that it is possible to extract statistics from github log
 */
namespace tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class DevStatsArtisanTest extends TestCase {

	/**
	 * 
	 */
	public function test_dev_stats() {
		$exitCode = Artisan::call("dev:stats");
		$this->assertEquals($exitCode, 0, "Error on artisan dev:stats");		
	}	
}
