<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class OsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_OS_identification()
    {
        $this->assertTrue(true);
                
        if (PHP_OS == "WINNT") {
        	$this->assertEquals("Windows NT", php_uname('s'));
        	$this->assertEquals("WINNT", PHP_OS);
        } else {
        	$this->assertEquals("Linux", PHP_OS);
        	$this->assertEquals("Linux", php_uname('s'));
        }
    }
}
