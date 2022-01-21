<?php

namespace App\Helpers;

use App\Models\Tenants\Metadata as Meta;
use App\Models\Schema;
use Illuminate\Support\Facades\Log;

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
	 * True if fillable in metadata field comments
	 * 
	 * @param unknown $table
	 * @param unknown $field
	 * @return boolean
	 * 
	 * TODO search metadata table with higher priority
	 */
	static function fillable($table, $field) {
		$meta = Schema::columnMetadata($table, $field);
		
		if (! $meta) return true;
		if (! array_key_exists('fillable', $meta)) return true;
		return ($meta['fillable'] == "yes");
	}
	
	/**
	 * True if the field must be displayed in the table list view
	 *
	 * @param unknown $table
	 * @param unknown $field
	 * @return boolean
	 */
	static function inTable($table, $field) {
		$subtype = self::subtype($table, $field);
		
		if (in_array($subtype, ['password_with_confirmation', 'password_confirmation'])) return false;
		$meta = Schema::columnMetadata($table, $field);
				
		if ($field == "id") return false;
		if (! $meta) return true;
		if (! array_key_exists('inTable', $meta)) return true;
		return ($meta['inTable'] == "yes");
	}
	
	/**
	 * True if the field must be displayed in the form views
	 *
	 * @param unknown $table
	 * @param unknown $field
	 * @return boolean
	 * 
	 * TODO search metadata table with higher priority
	 */
	static function inForm($table, $field) {
		$subtype = self::subtype($table, $field);
		
		$meta = Schema::columnMetadata($table, $field);
		if ($field == "id") return false;
		if (! $meta) return true;
		if (! array_key_exists('inForm', $meta)) return true;
		return ($meta['inForm'] == "yes");
	}
	
	/**
	 * Return a subtype for a field. The information is looked for either in the json encoded
	 * comment of a field or in the metadata table.
	 * @param unknown $table
	 * @param unknown $field
	 * @return string
	 * 
	 * TODO search metadata table with higher priority
	 */
	static public function subtype($table, $field) {
		// look in the field comment
		$meta = Schema::columnMetadata($table, $field);
		if ($meta && array_key_exists('subtype', $meta)) return $meta['subtype'];
			
		// Not found, maybe it's a derived field, look for root field
		if (preg_match('/(.*)(\_confirmation)/', $field, $matches)) {
			$root = $matches[1];
			
			// root field metadata
			$meta_root = Schema::columnMetadata($table, $root);
			if ($meta_root && array_key_exists('subtype', $meta_root) && ($meta_root['subtype'] == "password_with_confirmation")) {
				return "password_confirmation";
			}
		}
		
		if (preg_match('/(.*)(\_date)/', $field, $matches)) {
			$root = $matches[1];
						
			// root field metadata
			$meta_root = Schema::columnMetadata($table, $root);
			if ($meta_root && array_key_exists('subtype', $meta_root) && ($meta_root['subtype'] == "datetime_with_date_and_time")) {
				return "datetime_date";
			}
		}

		if (preg_match('/(.*)(\_time)/', $field, $matches)) {
			$root = $matches[1];
						
			// root field metadata
			$meta_root = Schema::columnMetadata($table, $root);
			if ($meta_root && array_key_exists('subtype', $meta_root) && ($meta_root['subtype'] == "datetime_with_date_and_time")) {
				return "datetime_time";
			}
		}
		
		// else look in the metadata table
		return Meta::subtype($table, $field);
	}
	
	/**
	 * Return a type for a field. 
	 * @param unknown $table
	 * @param unknown $field
	 * @return string
	 */
	static public function type($table, $field) {
		$full_type = Schema::columnType($table, $field);
				
		if (! $full_type) {
			$subtype = self::subtype($table, $field);
			if ($subtype == "password_confirmation") {
				return "password";
			} else if ($subtype == "password_with_confirmation") {
				return "password";
			} else if ($subtype == "datetime_date") {
				return "date";
			} else if ($subtype == "datetime_time") {
				return "time";
			}
		}
		$first = explode(' ', $full_type)[0];
		
		$pattern = '/(.*)(\(\d*\)*)/';
		if (preg_match($pattern, $first, $matches)) {
			// var_dump($matches);
			return $matches[1];
		}
		return $first;
	}
	
	/**
	 * Returns a list of field to display in the GUI from a column name in the database. This mechanism gives the possibility
	 * to hide some fields by returning and empty list or to generate several fields from one column like password and password confirm from 
	 * a single password column.
	 * 
	 * @param String $table
	 * @param String $field
	 * @return \App\Helpers\String[]
	 */
	static public function derived_fields(String $table, String $field) {
		if (in_array($field, ["id", "created_at", "updated_at"])) {
			return [];
		}
		
		$subtype = self::subtype($table, $field);
		
		if ($subtype == "password_with_confirmation") {
			return [$field, $field . "_confirmation"];
		} else if ($subtype == "datetime_with_date_and_time") {
			return [$field . "_date", $field . "_time"];
		} 
		return [$field];
	}
	
	/**
	 * Returns a list with fillable fields
	 * @param String $table
	 * @return array
	 */
	static public function fillable_fields(String $table) {
		$list = Schema::fieldList($table);
		if (! $list) return "";
		$full_list = []; 
		foreach ($list as $field) {
			if (! self::fillable($table, $field)) continue;
			$derived_flds = self::derived_fields($table, $field);
			foreach ($derived_flds as $new_field) {
				$full_list[] = $new_field;
			}
		}
		return $full_list;
	}
	
	/**
	 * List of fillable fields into a comma separated string
	 * @param String $table
	 * @return string
	 */
	static public function fillable_names (String $table) {
		$list = self::fillable_fields($table);
		if (! $list) return "";
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
		
		$dusk_field = ($table == "users") ? "name" : Schema::primaryIndex($table);
		if ($type == "edit") {
			return 'edit_{{ $' . $element . '->' . $dusk_field . ' }}';
		} else {
			return 'delete_{{ $' . $element . '->' . $dusk_field . ' }}';
		}
	}
	
	// ###############################################################################################################
	// Code Generation
	// ###############################################################################################################
	
	/**
	 * Generate code to display an element in a table list view
	 * 
	 * @param String $table
	 * @param String $field
	 * @return string
	 */
	static public function field_display (String $table, String $field) {
		$subtype = self::subtype($table, $field);
		$element = self::element($table);
		
		if ($subtype == "email") {
			return '<A HREF="mailto:{{$' . $element . '->' . $field . '}}">{{$' . $element . '->' . $field . '}}</A>';
		} elseif ($subtype == "checkbox") {
			return '<input type="checkbox" {{ ($' . $element . '->' . $field . ') ? "checked" : "" }}  onclick="return false;" />';
		}
		
		return '{{$' . $element . '->'. $field. '}}';
	}
	
	/**
	 * Generate code for a GUI form label
	 * 
	 * @param String $table
	 * @param String $field
	 * @return string
	 */
	static public function field_label (String $table, String $field) {
		$element = self::element($table);
		return '<label for="' . $field . '">{{__("' . $element . '.' . $field . '")}}</label>';
	}
	
	/**
	 * Generate code for input in an edit form
	 * 
	 * @param String $table
	 * @param String $field
	 * @return string
	 */
	static public function field_input_edit (String $table, String $field) {
		$type = "text";
		$element = self::element($table);
		$field_type = self::type($table, $field);
		$subtype = self::subtype($table, $field);
		
		if ($subtype == "checkbox") {
			return "<input type=\"checkbox\" class=\"form-control\" name=\"" . $field . "\" value=\"1\"  {{old('" . $field . "', $" . $element . "->" . $field . ") ? 'checked' : ''}}/>";
		}
		
		if ($subtype == "password_with_confirmation" || $subtype == "password_confirmation") {
			$type = "password";
			return '<input type="' . $type . '" class="form-control" name="' . $field . '" value="{{ old("' . $field . '") }}"/>';
		}
		
		$fkt = Schema::foreignKeyReferencedTable($table, $field);
		if ($fkt) {
			// the field is a foreign key
			$target_element = self::element($fkt);
			return '{!! Blade::selector("' . $field . '", $' . $target_element . '_list, $' . $element  . '->' . $field .') !!}';
		}
		
		$class = 'form-control';
		
		if ($field_type == "date") {
			$class .= ' datepicker';
		}
		
		if ($field_type == "time") {
			$class .= ' timepicker';
		}

		if ($subtype == "color") {
			$class .= ' colorpicker';
			$type = 'color';
		}
		
		return '<input type="' . $type 
			. '" class="' . $class .'" name="' 
			. $field . '" value="{{ old("' . $field . '", $' . $element . '->' . $field . ') }}"/>';
	}
	
	/**
	 * Generate code for input in an create form
	 * 
	 * @param String $table
	 * @param String $field
	 * @return string
	 */
	static public function field_input_create (String $table, String $field) {
		$type = "text";
		$element = self::element($table);
		$field_type = self::type($table, $field);
		$subtype = self::subtype($table, $field);
		
		if ($subtype == "checkbox") {
			return "<input type=\"checkbox\" class=\"form-control\" name=\"" . $field . "\" id=\"" . $field . "\" value=\"1\"  {{old('" . $field . "') ? 'checked' : ''}}/>";
		}
		
		if ($subtype == "password_with_confirmation" || $subtype == "password_confirmation") {
			$type = "password";
		}
		
		$fkt = Schema::foreignKeyReferencedTable($table, $field);
		if ($fkt) {
			// the field is a foreign key
			$target_element = self::element($fkt);
			return '{!! Blade::selector("' . $field . '", $' . $target_element . '_list, "") !!}';
		}
		
		$class = 'form-control';
		
		if ($field_type == "date") {
			$class .= ' datepicker';
		}
		
		if ($field_type == "time") {
			$class .= ' timepicker';
		}
		
		if ($subtype == "color") {
			$class .= ' colorpicker';
			$type = 'color';
		}
		
		return '<input type="' . $type 
			. '" class="' . $class . '" name="' 
			. $field . '" value="{{ old("' . $field . '") }}"/>';
	}
	
	/**
	 * Generate a delete button for a table
	 * 
	 * @param String $table
	 * @return string
	 */
	static public function button_delete (String $table) {
		$element = self::element($table);
		$dusk = self::dusk($table, $element, "delete");
		$primary_index = Schema::primaryIndex($table);
		
		
		$res = '<form action="{{ route("' . $element . '.destroy", $' . $element . '->' 
				. $primary_index . ')}}" method="post">' . "\n";
		$res .= "                   @csrf\n";
		$res .= "                   @method('DELETE')\n";
		$res .= "                   <button class=\"btn btn-danger\" type=\"submit\" dusk=\"$dusk\">{{__('general.delete')}}</button>\n";
		$res .= "                 </form>\n";
		return $res;
	}
	
	/**
	 * Generate an edit button for a table
	 * 
	 * @param String $table
	 * @return string
	 */
	static public function button_edit (String $table) {
		$primary_index = Schema::primaryIndex($table);
		$element = self::element($table);
		$id = $element . '->' . $primary_index;
		$dusk = self::dusk($table, $element, "edit");		
		$route = "{{ route('$element.edit', \$$id) }}";
		$label = "{{ __('general.edit') }}";
		return '<a href="' . $route . '" class="btn btn-primary" dusk="' . $dusk . '">' . $label . '</a>';
	}
	
	/**
	 * Generate a validation rule for an edit input field
	 * 
	 * @param String $table
	 * @param String $field
	 * @return string
	 */
	static public function field_rule_edit (String $table, String $field) {
		$subtype = self::subtype($table, $field);
		$element = self::element($table);
		$primary_index = Schema::primaryIndex($table);
		
		$rules = [];
		if (Schema::required($table, $field))  {
			$rules[] = "'required'";
		}
		
		if ($size = Schema::columnSize($table, $field)) {
			$rules[] = "'max:$size'";
		}
		
		if ($subtype == 'email') {
			$rules[] = "'email'";
		}
		
		$fkt = Schema::foreignKeyReferencedTable($table, $field);
		if ($fkt) {
			// the field is a foreign key
			$fkc = Schema::foreignKeyReferencedColumn($table, $field);
			$rules[] = "'exists:$fkt,$fkc'";

		} elseif (Schema::unique($table, $field)) {
			$id = ($primary_index == "id") ? "" : ", '$primary_index'";
			$rules[] = "Rule::unique('$table')->ignore(request('$element')$id)";
		}
		
		return  '[' . implode(', ', $rules) . ']';
	}
	
	/**
	 * Generate a validation rule for a create input field
	 * 
	 * @param String $table
	 * @param String $field
	 * @return string
	 */
	static public function field_rule_create (String $table, String $field) {
		$subtype = self::subtype($table, $field);
		
		$rules = [];
		if (Schema::required($table, $field))  {
			$rules[] = "'required'";
		}
		
		if ($size = Schema::columnSize($table, $field)) {
			$rules[] = "'max:$size'";
		}
		
		if ($subtype == 'email') {
			$rules[] = "'email'";
		}
		
		$fkt = Schema::foreignKeyReferencedTable($table, $field);
		if ($fkt) {
			// the field is a foreign key
			$fkc = Schema::foreignKeyReferencedColumn($table, $field);
			$rules[] = "'exists:$fkt,$fkc'";
			
		} elseif (Schema::unique($table, $field)) {
			$rules[] = "'unique:$table'";
		}			
		
		return  '[' . implode(', ', $rules) . ']';
	}
	
	/**
	 * Metadata all metadata for an individual field
	 * 
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
	static public function table_field_list (String $table) {
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
	static public function form_field_list (String $table) {
		$res = [];
		$list = self::fillable_fields($table);
		foreach ($list as $field) {
			if (! self::inForm($table, $field)) continue;
			// echo "adding $table $field to form\n";
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
				'table_field_list' => self::table_field_list($table),
				'form_field_list' => self::form_field_list($table),
				'button_edit' => self::button_edit($table),
				'button_delete' => self::button_delete($table),
				'primary_index' => Schema::primaryIndex($table),
		);
	}
}