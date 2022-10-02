<?php

/**
 * Test cases:
 *
 * Checks that it is possible to extract statistics from github log
 */
namespace tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class DevReviewArtisanTest extends TestCase {

	/**
	 * 
	 */
	public function test_dev_review() {
	    // Disabled because it is slow
	    $this->markTestSkipped('too slow and too specific to be run often');
	    
		$exitCode = Artisan::call("dev:review");
		$this->assertEquals($exitCode, 0, "Error on artisan dev:review");		
	}	
	
	/**
	 *
	 */
	public function test_dev_review_help() {
		$exitCode = Artisan::call("dev:review --help");
		$this->assertEquals($exitCode, 0, "Error on artisan dev:review");
	}
	
}
