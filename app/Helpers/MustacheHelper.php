<?php
namespace App\Helpers;

use App\Helpers\MetadataHelper as Meta;

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
		if (Self::is_absolute_path($template)) {
			$file = $template;
		} else {
			$file = Self::absolute_template_path($template);
		}
		$filename = str_ends_with($file, '.mustache') ? $file : $file . '.mustache';
		
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
	
	/**
	 * array('planet' => 'World')
	 * @param String $table
	 * @return string[]
	 */
	public static function metadata(String $table) {
		return array(
			'table' => $table,
			'class_name' => Meta::class_name($table),
			'fillable_names' => '"name", "email", "admin", "active"',
			'element' => Meta::element($table),
			'fillable' => [
					['name' => 'fld1', 'field_input' => 'field_1', 'field_display' => 'display_1'],
					['name' => 'fld2', 'field_input' => 'field_2', 'field_display' => 'display_2'],
					['name' => 'fld3', 'field_input' => 'field_3', 'field_display' => 'display_3'],
			],
			'list' => [
					['name' => 'fld1', 'field_input' => 'field_1', 'field_display' => 'display_1'],
					['name' => 'fld2', 'field_input' => 'field_2', 'field_display' => 'display_2'],
					['name' => 'fld3', 'field_input' => 'field_3', 'field_display' => 'display_3'],
			]
				
		);
	}
}