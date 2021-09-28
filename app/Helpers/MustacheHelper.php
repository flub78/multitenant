<?php
namespace App\Helpers;

use App\Helpers\MetadataHelper as Meta;
use Exception;

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
	 * Convention for the template name
	 *
	 * @param String $table
	 * @param String $template
	 * @param String $template_file (by default)
	 */
	public static function template_file(String $table, String $template, String $template_file = "") {
		$class_name = Meta::class_name($table);
		$element = Meta::element($table);
		
		$file = $template_file;
		if ($template == "controller") {
			$file =  'app/Http/Controllers/Tenants/Controller.php.mustache';
		} elseif ($template == "model") {
			$file =  'Model.php.mustache';
		} elseif ($template == "request") {
			$file =  'Request.php.mustache';
		} elseif ($template == "index") {
			$file =  'resources/views/tenants/index.blade.php.mustache';
		} elseif ($template == "create") {
			$file =  'resources/views/tenants/create.blade.php.mustache';
		} elseif ($template == "edit") {
			$file =  'resources/views/tenants/edit.blade.php.mustache';
		} elseif ($template == "english") {
			$file =  'lang.php.mustache';
		} elseif ($template == "french") {
			$file =  'lang.php.mustache';
		} elseif ($template == "test_model") {
			$file =  'test_model.php.mustache';
		} elseif ($template == "test_controller") {
			$file =  'test_controller.php.mustache';
		}
		return self::template_filename($file);
	}

	/**
	 * Convention for the generated file name
	 *
	 * @param String $table
	 * @param String $template
	 * @param String $result_file  (by default)
	 */
	public static function result_file(String $table, String $template, bool $installation = false) {
		$class_name = Meta::class_name($table);
		$element = Meta::element($table);
		
		$file = "";
		if ($template == "controller") {
			$file =  "app/Http/Controllers/Tenants/" . $class_name . "Controller.php";
		} elseif ($template == "model") {
			$file =  "app/Models/Tenants/" . $class_name . '.php';
		} elseif ($template == "request") {
			$file =  "app/Http/Requests/Tenants/" . $class_name . "Request.php";
		} elseif ($template == "index") {
			$file =  'resources/views/tenants/' . $element . '/index.blade.php';
		} elseif ($template == "create") {
			$file =  'resources/views/tenants/' . $element . '/create.blade.php';
		} elseif ($template == "edit") {
			$file =  'resources/views/tenants/' . $element . '/edit.blade.php';
		} elseif ($template == "english") {
			$file =  'resources/lang/en/' . $element . '.php';
		} elseif ($template == "french") {
			$file =  'resources/lang/fr/' . $element . '.php';
		} elseif ($template == "test_model") {
			$file =  'tests/Unit/Tenant/' . $class_name . 'ModelTest.php';
		} elseif ($template == "test_controller") {
			$file =  'tests/Feature/Tenants/' . $class_name . 'ControllerTest.php';
		}
		return self::result_filename($file, $installation);
	}
	
	/**
	 * @param string $result
	 * @return string
	 */
	public static function result_filename(string $result, bool $installation = false) {
		if (Self::is_absolute_path($result)) {
			return $result;
		}
		$dir = dirname($result);
		$dirname = ($dir == '.') ? '' : $dir . DIRECTORY_SEPARATOR;
		$basename = basename($result);
		if (str_ends_with($basename, '.mustache')) {
			$basename = substr($basename, 0, -9);
		}
		
		if ($installation) {
			$path = getcwd() . DIRECTORY_SEPARATOR . $dirname . $basename;
		} else {
			$path = getcwd() . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'results' 
				. DIRECTORY_SEPARATOR . $dirname . $basename;
		}
		echo "\npath = $path\n";
		$realpath = realpath($path);
		echo "realpath = $realpath\n";
		return $realpath;
	}
	
	/**
	 * Returns all metadata associated to a table
	 * @param String $table
	 * @return string[]
	 */
	public static function metadata(String $table) {
		return Meta::metadata($table);
	}
}