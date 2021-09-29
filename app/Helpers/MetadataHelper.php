<?php

namespace App\Helpers;

use App\Models\Tenants\Metadata as Meta;
use App\Models\Schema;


/**
 * Metadata interface
 *
 * Central point to get metadata associated to a table
 * 
 * @author frederic
 *        
 */
class MetadataHelper {

	/**
	 * Transform a string into CamelCase
	 * @param unknown $string
	 * @param boolean $capitalizeFirstCharacter
	 * @return mixed
	 */
	static public function underscoreToCamelCase($string, $capitalizeFirstCharacter = false) {
		$str = str_replace('_', '', ucwords($string, '_'));

		if (!$capitalizeFirstCharacter) {
			$str = lcfirst($str);
		}
		return $str;
	}

	/**
	 * Return the model class name
	 * @param string $table
	 * @return mixed
	 */
	static public function class_name(string $table) {
		return self::underscoreToCamelCase(rtrim($table, 's'), true);
	}

	/**
	 * Element name
	 * @param string $table
	 * @return string
	 */
	static public function element(string $table) {
		return rtrim($table, 's');
	}

	/**
	 * Returns a list with fillable fields
	 * @param String $table
	 * @return array
	 */
	static public function fillable_fields(String $table) {
		$list = Schema::fieldList($table);
		$list = array_diff($list, ["id", "created_at", "updated_at"]);  // remove some values
		$list = array_values($list); // re-index
		return $list;
	}
	
	/**
	 * List of fillable fields into a comma separated string
	 * @param String $table
	 * @return string
	 */
	static public function fillable_names (String $table) {
		$list = self::fillable_fields($table);
		array_walk($list, function(&$x) {$x = "\"$x\"";}); // put double quotes around each element
		$res = implode(', ', $list); // transform into string
		return $res;
	}
	
	/**
	 * Generate a dusk anchor
	 * @param String $table
	 * @param String $element
	 * @param String $type
	 * @return string
	 */
	static public function dusk(String $table, String $element, String $type="edit") {
		$dusk_field = ($table == "users") ? "name" : "id";
		if ($type == "edit") {
			return 'edit_{{ $' . $element . '->' . $dusk_field . ' }}';
		} else {
			return 'delete_{{ $' . $element . '->' . $dusk_field . ' }}';
		}
	}
	
	// ###############################################################################################################
	
	static public function field_display (String $table, String $field) {
		$subtype = Meta::subtype($table, $field);
		$element = self::element($table);
		
		if ($subtype == "email") {
			return '<A HREF="mailto:{{$' . $element . '->' . $field . '}}">{{$' . $element . '->' . $field . '}}</A>';
		} elseif ($subtype == "checkbox") {
			return '<input type="checkbox" {{ ($' . $element . '->' . $field . ') ? "checked" : "" }}  onclick="return false;" />';
		}
		
		return '{{$' . $element . '->'. $field. '}}';
	}
	
	static public function field_label (String $table, String $field) {
		$element = self::element($table);
		return '<label for="' . $field . '">{{__("' . $element . '.' . $field . '")}}</label>';
	}
	
	static public function field_input_edit (String $table, String $field) {
		$type = "text";
		$element = self::element($table);
		
		return '<input type="' . $type . '" class="form-control" name="' . $field . '" value="{{ old("' . $field . '", $' . $element . '->' . $field . ') }}"/>';
	}
	
	static public function field_input_create (String $table, String $field) {
		$type = "text";
		$element = self::element($table);
		
		return '<input type="' . $type . '" class="form-control" name="' . $field . '" value="{{ old("' . $field . '") }}"/>';
	}
	
	static public function button_delete (String $table) {
		$element = self::element($table);
		$dusk = self::dusk($table, $element, "delete");
		
		$res = '<form action="{{ route("' . $element . '.destroy", $' . $element . '->id)}}" method="post">' . "\n";
		$res .= "                   @csrf\n";
		$res .= "                   @method('DELETE')\n";
		$res .= "                   <button class=\"btn btn-danger\" type=\"submit\" dusk=\"$dusk\">{{__('general.delete')}}</button>\n";
		$res .= "                 </form>\n";
		return $res;
	}
	
	static public function button_edit (String $table) {
		$element = self::element($table);
		$id = $element . '->id';
		$dusk = self::dusk($table, $element, "edit");		
		$route = "{{ route('$element.edit', \$$id) }}";
		$label = "{{ __('general.edit') }}";
		return '<a href="' . $route . '" class="btn btn-primary" dusk="' . $dusk . '">' . $label . '</a>';
	}
	
	static public function field_rule_edit (String $table, String $field) {
		$subtype = Meta::subtype($table, $field);
		$element = self::element($table);
		
		$rules = [];
		if (Schema::required($table, $field))  {
			$rules[] = "'required'";
		}
		
		if ($size = Schema::columnSize($table, $field)) {
			$rules[] = "'max:$size'";
		}
		
		if (Schema::unique($table, $field)) {
			$rules[] = "Rule::unique('$table')->ignore(request('$element'))";
		}
		
		if ($subtype == 'email') {
			$rules[] = "'email'";
		}
		
		return  '[' . implode(', ', $rules) . ']';
	}
	
	static public function field_rule_create (String $table, String $field) {
		$subtype = Meta::subtype($table, $field);
		
		$rules = [];
		if (Schema::required($table, $field))  {
			$rules[] = "'required'";
		}
		
		if ($size = Schema::columnSize($table, $field)) {
			$rules[] = "'max:$size'";
		}
		
		if (Schema::unique($table, $field)) {
			$rules[] = "'unique:$table'";
		}
		
		if ($subtype == 'email') {
			$rules[] = "'email'";
		}
		
		return  '[' . implode(', ', $rules) . ']';
	}
	
	/**
	 * Metadata for an individual field
	 * @param String $table
	 * @param String $field
	 * @return Array[]
	 */
	static public function field_metadata(String $table, String $field) {
		return ['name' => $field, 
				'display' => self::field_display($table, $field), 
				'label' => self::field_label($table, $field),
				'input_edit' => self::field_input_edit($table, $field), 
				'input_create' => self::field_input_create($table, $field), 
				'rule_edit' => self::field_rule_edit($table, $field), 
				'rule_create' => self::field_rule_create($table, $field), 
		];
	}
	
	/**
	 * An array of metadata for all fillable fields
	 * @param String $table
	 * @return string[][]
	 */
	static public function table_list (String $table) {
		$res = [];
		$list = self::fillable_fields($table);
		if ($table == "users") {
			// Todo store this information in database
			$list = ["name", "email", "admin", "active"];
		}
		foreach ($list as $field) {
			$res[] = self::field_metadata($table, $field);
		}
		return $res;
	}
	
	/**
	 * An array of metadata for all fillable fields
	 * @param String $table
	 * @return string[][]
	 */
	static public function field_list (String $table) {
		$res = [];
		$list = self::fillable_fields($table);
		if ($table == "users") {
			// Todo store this information in database
			$list = ["name", "email", "admin", "active"];
		}
		foreach ($list as $field) {
			$res[] = self::field_metadata($table, $field);
		}
		return $res;
	}
	
	/**
	 * All the metadata for a table
	 *
	 * @param String $table
	 * @return array[]
	 */
	static public function metadata(String $table) {
		return array(
				'table' => $table,
				'class_name' => self::class_name($table),
				'fillable_names' => self::fillable_names($table),
				'element' => self::element($table),
				'table_list' => self::table_list($table),
				'field_list' => self::field_list($table),
				'button_edit' => self::button_edit($table),
				'button_delete' => self::button_delete($table),
		);
	}
}