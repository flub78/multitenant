<?php
namespace App\Helpers;

use App\Helpers\MetadataHelper as Meta;
use App\Helpers\CodeGenerator as CG;
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
	
	/**
	 * True when a path is absolute (starts from root device)
	 * 
	 * @param unknown $path
	 * @throws Exception
	 * @return boolean
	 */
	public static function is_absolute_path($path) {
		if($path === null || $path === '') throw new Exception("Empty path");
		return $path[0] === DIRECTORY_SEPARATOR || preg_match('~\A[A-Z]:(?![^/\\\\])~i',$path) > 0;
	}
	
	
	/**
	 * Returns the absolute filename of a template.
	 * 
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
		
		if (!file_exists($filename)) throw new Exception("template $filename not found");
		return $filename;
	}
	
	/**
	 * Template name relative to templates directory
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
			$file = implode(DIRECTORY_SEPARATOR, ['app', 'Http', 'Controllers', 'Tenants', 'Controller.php.mustache']);
			
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
			$file =  'tests/Unit/Tenants/test_model.php.mustache';
			
		} elseif ($template == "test_controller") {
			$file =  'tests/Feature/Tenants/test_controller.php.mustache';
			
		} elseif ($template == "test_dusk") {
			$file =  'tests/Browser/Tenants/test_dusk.php.mustache';
			
		} elseif ($template == "translation") {
			$file =  'translation.php.mustache';

		} elseif ($template == "factory") {
			$file =  'factory.php.mustache';
			
		} else {
			throw new Exception("unsupported template: $template");
		}
		return self::template_filename($file);
	}

	/**
	 * Generated file name relative to results directory
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
			$file = implode(DIRECTORY_SEPARATOR, ['app', 'Http', 'Controllers', 'Tenants', $class_name . 'Controller.php']);			
			
		} elseif ($template == "model") {
			$file = implode(DIRECTORY_SEPARATOR, ['app', 'Models', 'Tenants', $class_name . '.php']);
			
		} elseif ($template == "request") {
			$file =  "app/Http/Requests/Tenants/" . $class_name . "Request.php";
			$file = implode(DIRECTORY_SEPARATOR, ['app', 'Http', 'Requests', 'Tenants', $class_name . 'Request.php']);
			
		} elseif ($template == "index") {
			if ($table != "users") {
				$file = implode(DIRECTORY_SEPARATOR, ['resources', 'views', 'tenants', $element ,'index.blade.php']);
			} else {
				$file = implode(DIRECTORY_SEPARATOR, ['resources', 'views', 'users', 'index.blade.php']);
			}
			
		} elseif ($template == "create") {
			if ($table != "users") {
				$file = implode(DIRECTORY_SEPARATOR, ['resources', 'views', 'tenants', $element, 'create.blade.php']);
			} else {
				$file = implode(DIRECTORY_SEPARATOR, ['resources', 'views', 'users', 'create.blade.php']);
			}
			
		} elseif ($template == "edit") {
			if ($table != "users") {
				$file = implode(DIRECTORY_SEPARATOR, ['resources', 'views', 'tenants', $element, 'edit.blade.php']);
			} else {
				$file = implode(DIRECTORY_SEPARATOR, ['resources', 'views', 'users', 'edit.blade.php']);
			}
			
		} elseif ($template == "english") {
			$file = implode(DIRECTORY_SEPARATOR, ['resources', 'lang', 'en', $element . '.php']);
			
		} elseif ($template == "french") {
			$file = implode(DIRECTORY_SEPARATOR, ['resources', 'lang', 'fr', $element . '.php']);
			
		} elseif ($template == "test_model") {
			$file = implode(DIRECTORY_SEPARATOR, ['tests', 'Unit', 'Tenants', $class_name . 'ModelTest.php']);			
			
		} elseif ($template == "test_controller") {
			$file = implode(DIRECTORY_SEPARATOR, ['tests', 'Feature', 'Tenants', $class_name . 'ControllerTest.php']);
		
		} elseif ($template == "test_dusk") {
			$file = implode(DIRECTORY_SEPARATOR, ['tests', 'Browser', 'Tenants', $class_name . 'Test.php']);
			
		} elseif ($template == "factory") {
			$file = implode(DIRECTORY_SEPARATOR, ['database', 'factories', 'Tenants', $class_name . 'Factory.php']);
		}
	
		return self::result_filename($file, $installation);
	}
	
	/**
	 * Absolute path of a result file, either in the result directory or in installation directory
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
		return $path;
	}
	
	/**
	 * Translation file, result or installation file
	 *
	 * @param String $file
	 * @param String $template
	 * @param String $result_file  (by default)
	 */
	public static function translation_result_file(String $file, String $lang = 'fr', bool $installation = false) {
		
		$file = implode(DIRECTORY_SEPARATOR, ['resources', 'lang', $lang, "$file.php"]);
		return self::result_filename($file, $installation);
	}
	
	/**
	 * Return the name of the English language file containing the associative array to translate
	 * 
	 * @param String $elt
	 */
	public static function source_language_file (String $elt) {
		return implode(DIRECTORY_SEPARATOR, ['resources', 'lang', 'en', "$elt.php"]);	
	}
	
	/**
	 * Returns all metadata associated to a table
	 * 
	 * @param String $table
	 * @return string[]
	 */
	public static function metadata(String $table) {
		return CG::metadata($table);
	}
}