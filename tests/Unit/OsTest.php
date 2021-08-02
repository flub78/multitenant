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
        
        echo "php_uname('s') = " . php_uname('s') . "\n";
        echo "PHP_OS = " . PHP_OS . "\n";
        echo "php_uname() = " . php_uname() . "\n";
        
    }
}
