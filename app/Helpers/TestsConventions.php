<?php
namespace App\Helpers;

use App\Models\Tenants\Configuration;
use Exception;

/**
 * This class enforces conventions to generate test data. The idea is to generate test data
 * in a predictable way to know by advance the values to expect. The tests generates
 * test data according to the conventions, processes them and verifies the expected 
 * values using the same conventions.
 * 
 * These conventions are used to generate fakers and the methods 
 * to check the results.
 * 
 * String data will contain context related data like table and column name,
 * the number of occurrences and some pseudo random information.
 * 
 * Integer and float data will contain pseudo random values, etc.
 *  
 * @author frederic
 *
 */
class TestsConventions {

	static public function int_value (int $n, $min = 0, $max = 10000) {
		return 0;
	}
	
}