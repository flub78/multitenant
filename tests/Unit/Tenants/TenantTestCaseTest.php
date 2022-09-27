<?php

namespace tests\Unit;

use Tests\TenantTestCase;

class TenantTestCaseTest extends TenantTestCase

{
        
    public function test_assert_occurences_in_string () {    
    	$this->assertTrue(true);
    	
    	$this->assertOccurencesInString('ab', 'xxabyyabzz', 2);
    	$this->assertOccurencesInString('ab', 'abxxabyyabzz', 3);
    	$this->assertOccurencesInString('ab', 'abxxabyyabzz', 2, 'xxa');
    	
    	$this->assertOccurencesInString('ab', 'abxxabyyabzz', 1, '', 'byy');
    	$this->assertOccurencesInString('ab', 'abxxabyyabzz', 1, 'bx', 'ya');
    }
    
}
