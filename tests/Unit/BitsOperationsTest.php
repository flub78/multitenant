<?php

namespace Tests\Unit;


use Tests\TestCase;
use App\Helpers\BitsOperationsHelper as BO;

class BitsOperationsTest extends TestCase {

	public function test_bit_at() {
		$this->AssertEquals('0', BO::bit_at(0, 0));
		$this->AssertEquals('0', BO::bit_at(0, 10));

		$this->AssertEquals('1', BO::bit_at(5, 0));
		$this->AssertEquals('0', BO::bit_at(5, 1));
		$this->AssertEquals('1', BO::bit_at(5, 2));
		$this->AssertEquals('0', BO::bit_at(5, 3));
		$this->AssertEquals('0', BO::bit_at(5, 4));
	}
	
	public function test_to_string() {
		$this->AssertEquals('0', BO::to_string(""));
		$this->AssertEquals('0', BO::to_string(0));
		$this->AssertEquals('111', BO::to_string(7));
		$this->AssertEquals('101', BO::to_string(5));
		$this->AssertEquals('11000', BO::to_string(24));
		
		$this->AssertEquals('0', BO::to_string("", "hexa"));
		$this->AssertEquals('0', BO::to_string(0), "hexa");
		$this->AssertEquals('7', BO::to_string(7, "hexa"));
		$this->AssertEquals('1F', BO::to_string(31, $mode = "hexa"));		
	}
	
	public function test_set_and_clear () {
		$bf = 0;
		BO::set($bf, 1);
		$this->AssertEquals(2, $bf);
		
		BO::set($bf, 2);
		BO::set($bf, 2);
		$this->AssertEquals(6, $bf);
		
		BO::clear($bf, 1);
		$this->AssertEquals(4, $bf);

		BO::clear($bf, 2);
		$this->AssertEquals(0, $bf);
		
		$previous = $bf;
		BO::set($bf, 64);
		$this->AssertEquals($previous, $bf);
		
		BO::clear($bf, 64);
		$this->AssertEquals($previous, $bf);
		
		
	}
	
}
