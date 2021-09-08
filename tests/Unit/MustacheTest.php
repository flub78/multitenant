<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Just a small test to insure that the mustache templating mechanism is correctly in place
 * and to make some investigation.
 * 
 * @author frederic
 *
 */
class MustacheTest extends TestCase {

	public function test_basic_mustache() {
		
		$mustache = new \Mustache_Engine;
		$this->assertNotNull($mustache);
		
		$rendered= $mustache->render('Hello, {{planet}}!', array('planet' => 'World')); // "Hello, World!"
		$this->assertEquals("Hello, World!", $rendered);
	}
}
