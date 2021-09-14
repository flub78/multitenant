<?php
namespace App\Helpers;

  
/**
 * Some function related to code generation
 * 
 * @author Frederic
 *
 */
class MustacheHelper {
	
	public const RESULT_SUBDIR = '\\build\\results\\';
	public const INSTALLATION_DIR = '';
	

	/**
	 * Returns the absolute path of a template file
	 * @param string $template
	 * @return string
	 */
	private static function absolute_template_path(string $template) {
		return 	getcwd() . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $template;
	}
	
	public static function is_absolute_path($path) {
		if($path === null || $path === '') throw new Exception("Empty path");
		return $path[0] === DIRECTORY_SEPARATOR || preg_match('~\A[A-Z]:(?![^/\\\\])~i',$path) > 0;
	}
	
	/**
	 * @param string $template
	 * @return string
	 */
	public static function template_dirname(string $template = "") {
		$dir = Self::absolute_template_path($template);
		if (!is_dir($dir)) return "";
		return $dir;
	}
	
	/**
	 * @param string $template
	 * @return string|string|\App\Helpers\string
	 */
	public static function template_filename(string $template = "") {
		echo "\n";
		echo "\ntemplate_filename($template)";
		if (Self::is_absolute_path($template)) {
			echo " absolute ";
			$file = $template;
		} else {
			echo " relative ";
			$file = Self::absolute_template_path($template);
		}
		$filename = str_ends_with($file, '.mustache') ? $file : $file . '.mustache';
		
		echo "\nfilename = $filename";
		
		if (!file_exists($filename)) return "";
		return $filename;
	}
	
	
	/**
	 * @param string $result
	 * @return string
	 */
	public static function result_filename(string $result) {
		if (Self::is_absolute_path($result)) {
			return $result;
		}
		$dir = dirname($result);
		$dirname = ($dir == '.') ? '' : $dir . DIRECTORY_SEPARATOR;
		$basename = basename($result);
		if (str_ends_with($basename, '.mustache')) {
			$basename = substr($basename, 0, -9);
		}
			
		return getcwd() . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'results' 
				. DIRECTORY_SEPARATOR . $dirname . $basename;
	}
}