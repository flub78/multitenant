<?php

namespace tests\Unit;

use Tests\TestCase;

use App\Helpers\TestsConventions as TC;

class TestsConventionsTest extends TestCase

{
        
    public function test_int_value () {    
    	$this->assertEquals(0, TC::int_value(0));
    }
    
    
}
