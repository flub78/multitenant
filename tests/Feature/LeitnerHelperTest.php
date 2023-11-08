<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Helpers\LeitnerHelper;

/**
 * Tests for the LeitnerHelper class.
 * 
 * As soon as a class need to use storage_path a container is required and it becomes a feature test.
 * 
 * @author frederic
 *
 */
class LeitnerHelperTest extends TestCase
{

	public function test_getLevel() {
		$this->assertEquals(2, LeitnerHelper::getLevel(1));
		$this->assertEquals(3, LeitnerHelper::getLevel(2));
		$this->assertEquals(2, LeitnerHelper::getLevel(3));
		$this->assertEquals(4, LeitnerHelper::getLevel(4));
		$this->assertEquals(2, LeitnerHelper::getLevel(5));
		$this->assertEquals(3, LeitnerHelper::getLevel(6));
		$this->assertEquals(2, LeitnerHelper::getLevel(7));
		$this->assertEquals(5, LeitnerHelper::getLevel(8));
		$this->assertEquals(2, LeitnerHelper::getLevel(9));
		$this->assertEquals(3, LeitnerHelper::getLevel(10));
		$this->assertEquals(2, LeitnerHelper::getLevel(11));
		$this->assertEquals(4, LeitnerHelper::getLevel(12));
		$this->assertEquals(2, LeitnerHelper::getLevel(13));
		$this->assertEquals(3, LeitnerHelper::getLevel(14));
		$this->assertEquals(2, LeitnerHelper::getLevel(15));
		$this->assertEquals(6, LeitnerHelper::getLevel(16));
		$this->assertEquals(2, LeitnerHelper::getLevel(17));
		$this->assertEquals(3, LeitnerHelper::getLevel(18));
		$this->assertEquals(2, LeitnerHelper::getLevel(19));
		$this->assertEquals(4, LeitnerHelper::getLevel(20));
		$this->assertEquals(2, LeitnerHelper::getLevel(21));
		$this->assertEquals(3, LeitnerHelper::getLevel(22));

		// for ($i = 1; $i < 120; $i++) {
		// 	echo $i . " => " . LeitnerHelper::getLevel($i) . "\n";
		// }
	}
}
