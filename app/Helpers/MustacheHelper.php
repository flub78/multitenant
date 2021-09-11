<?php
namespace App\Helpers;

  
/**
 * Some function related to code generation
 * 
 * @author Frederic
 *
 */
class MustacheHelper {
	
	public const TEMPLATE_SUBDIR = '\\build\\templates\\';
	public const RESULT_SUBDIR = '\\build\\results\\';
	public const INSTALLATION_DIR = '';
	
	/**
	 * Returns the absolute path of a template file
	 * @param string $template
	 * @return string
	 */
	private static function absolute_template_path(string $template) {
		return 	getcwd() . Self::TEMPLATE_SUBDIR . $template;
	}
	
	private static function isAbsolutePath($path) {
		if (!is_string($path)) {
			$mess = sprintf('String expected but was given %s', gettype($path));
			throw new \InvalidArgumentException($mess);
		}
		if (!ctype_print($path)) {
			$mess = 'Path can NOT have non-printable characters or be empty';
			throw new \DomainException($mess);
		}
		// Optional wrapper(s).
		$regExp = '%^(?<wrappers>(?:[[:print:]]{2,}://)*)';
		// Optional root prefix.
		$regExp .= '(?<root>(?:[[:alpha:]]:/|/)?)';
		// Actual path.
		$regExp .= '(?<path>(?:[[:print:]]*))$%';
		$parts = [];
		if (!preg_match($regExp, $path, $parts)) {
			$mess = sprintf('Path is NOT valid, was given %s', $path);
			throw new \DomainException($mess);
		}
		if ('' !== $parts['root']) {
			return true;
		}
		return false;
	}
	
	public static function template_dirname(string $template = "") {
		$dir = Self::absolute_template_path($template);
		if (!is_dir($dir)) return "";
		return $dir;
	}
	
	public static function template_filename(string $template = "") {
		if (Self::isAbsolutePath($template)) {
			$file = $template;
		} else {
			$file = Self::absolute_template_path($template);
		}
		$filename = str_ends_with($file, '.mustache') ? $file : $file . '.mustache';
		
		if (!file_exists($filename)) return "";
		return $filename;
	}
	
	
	public static function result_filename(string $result) {
		if (Self::isAbsolutePath($result)) {
			return $result;
		}
		$dir = dirname($result);
		$dirname = ($dir == '.') ? '' : $dir . DIRECTORY_SEPARATOR;
		$basename = basename($result);
		if (str_ends_with($basename, '.mustache')) {
			$basename = substr($basename, 0, -9);
		}
			
		return getcwd() . Self::RESULT_SUBDIR . $dirname . $basename;
	}
}