<?php

namespace App\Helpers;

/**
 * Bit operations
 *
 * A set of operations to access individual bits of an integer
 * 
 * @author frederic
 * @reviewed 2022-04-03
 *        
 */
class BitsOperationsHelper {

	/**
	 * Value of a bit for an index
	 * 
	 * @param integer $bitfield
	 * @param integer $index
	 * @return 0 | 1
	 */
	static public function bit_at($bitfield, $index) {
		return ($bitfield >> $index) & 1;
	}

	/**
	 * clear a bit
	 * @param integer $bitfield
	 * @param integer $index
	 */
	static public function clear(&$bitfield, $index) {
		$mask = ~(1 << $index);
		$bitfield &= $mask;
	}
	
	/**
	 * @param integer $bitfield
	 * @param integer $index
	 */
	static public function set(&$bitfield, $index) {
		$mask = 1 << $index;
		$bitfield |= $mask;
	}
	
	/**
	 * Returns an hexadecimal or binary string
	 *  
	 * @param integer $bitfield
	 * @param string $mode
	 * @return string
	 */
	static public function to_string($bitfield, $mode = "binary") {
		if ("binary" == $mode) 
			return sprintf('%b', $bitfield);
		return sprintf('%X', $bitfield);
	}
}