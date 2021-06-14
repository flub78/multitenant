<?php
namespace App\Helpers;

use Exception;

  
/**
 * Some routines to handle directories
 * 
 * @author frederic
 *
 */
class DirHelper {
	
	/**
	 * Recusively remove a directory and its content
	 * 
	 * @param string $dir
	 */
	static public function rrmdir(string $dir) {
		if (is_dir($dir)) {
			
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
						DirHelper::rrmdir($dir. DIRECTORY_SEPARATOR .$object);
					else {
						unlink($dir. DIRECTORY_SEPARATOR .$object);
					}
				}
			}
			for ($i=0; $i < 5; $i++) {
				gc_collect_cycles();
				try {
					rmdir($dir);
					break;
				} catch (Exception $e) {
					$msg = $e->getMessage();
					$next = 100 * $i;
					echo "\n>>>>>>>>> Exception $msg, next attempt in $next usec\n";
					usleep($next);
				}
			}
		}
	}
	
}