<?php

namespace tests\Unit;

use Tests\TenantTestCase;

use App\Models\Tenants\Configuration;
// use database\factories\ConfigurationFactory;
use App\Helpers\Config;

class ConfigHelperTest extends TenantTestCase

{
        
    public function test_unknow_config_value () {    
    	$this->assertNull(Config::config('unknown_config_value'));
    }
    
    public function test_not_overwritten_config_value () {
    	$key = "app.locale";
    	
    	$locale = config($key);
    	$this->assertEquals("en", $locale); // by default
    	
    	$env_elt = Configuration::where('key', $key)->first();
    	$this->assertNull($env_elt);
    	$this->assertEquals($locale, Config::config($key), "When nothing in database returns the config value");		
    			
    	$cfg = Configuration::factory()->create(['key' => $key, 'value' => 'de']);
    	$env_elt = Configuration::where('key', $key)->first();
    	$this->assertEquals("de", Config::config($key), "When nothing in database returnsn it");    	
    }
    
    public function test_set() {
    	$timezone = 'Europe/Paris';
    	
    	Config::set('app.timezone', $timezone);
    	$back = Config::config('app.timezone');
    	$this->assertEquals($timezone, $back);
    	
    	$timezone = 'CEST';
    	Config::set('app.timezone', $timezone);
    	$back = Config::config('app.timezone');
    	$this->assertEquals($timezone, $back);
    }
    
}
