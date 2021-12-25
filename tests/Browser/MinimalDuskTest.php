<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Test Dusk installation and Internet connection
 * 
 * run with
 * php artisan dusk --colors=always --browse tests/Browser/MinimalDuskTest.php
 * 
 * @author frederic
 *
 */
class MinimalDuskTest extends DuskTestCase
{
    /**
     * A Dusk test example
     *
     * @return void
     */
    public function testGoogle()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://www.google.fr')
                    ->assertSee('Google');
        
            $browser->screenshot('Google_from_minimal');
        });
    }
    
    /**
     * A Dusk test example
     *
     * @return void
     */
    public function testMETAR()
    {
    	$this->browse(function (Browser $browser) {
    		$lfat = "https://www.aviationweather.gov/metar/data?ids=LFAT&format=decoded&hours=0&taf=off&layout=on";
    		$title = "AWC - METeorological Aerodrome Reports (METARs)";
    		
    		$browser->visit($lfat)
    		->assertSee('Ceiling:')
    		->assertTitle($title);
    		
    	});
    }
    
}
