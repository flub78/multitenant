<?php
namespace App\Helpers;

use Exception;

  
/**
 * Some routines to handle directories
 * 
 * @author frederic
 *
 */
class LeitnerHelper {
	
	/* returns an integer which is the level to study according to the day of the training
		day		level 
		1		2
		2		3
		3		2
		4		4
		5		2
		6		3
		7		2
		8		5
	*/
	static public function getLevel($day) {
		if ($day % 2 == 1) {
			return 2;
		} elseif ($day % 4 == 2) {
			return 3;
		}  elseif ($day % 8 == 4) {
			return 4;
		} elseif ($day % 16 == 8) {
			return 5;
		}  elseif ($day % 32 == 16) {
			return 6;
		} else {
			return 7;
		}
	}
}