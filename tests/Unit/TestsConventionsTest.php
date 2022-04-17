<?php

namespace tests\Unit;

use Tests\TenantTestCase;

use App\Helpers\TestsConventions as TC;

class TestsConventionsTest extends TenantTestCase

{
        
    public function test_int_value () {    
    	$this->assertEquals(0, TC::int_value(0));
    }
    
    
}
