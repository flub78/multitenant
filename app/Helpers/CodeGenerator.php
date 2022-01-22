<?php

namespace App\Helpers;

use App\Helpers\MetadataHelper as Meta;

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
class CodeGenerator {

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
		$subtype = Meta::subtype($table, $field);
		$element = Meta::element($table);
		
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
		$element = Meta::element($table);
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
		$element = Meta::element($table);
		$field_type = Meta::type($table, $field);
		$subtype = Meta::subtype($table, $field);
		
		if ($subtype == "checkbox") {
			return "<input type=\"checkbox\" class=\"form-control\" name=\"" . $field . "\" value=\"1\"  {{old(\"" . $field . "\", $" . $element . "->" . $field . ") ? 'checked' : ''}}/>";
		}
		
		if ($subtype == "password_with_confirmation" || $subtype == "password_confirmation") {
			$type = "password";
			return '<input type="' . $type . '" class="form-control" name="' . $field . '" value="{{ old("' . $field . '") }}"/>';
		}
		
		$fkt = Schema::foreignKeyReferencedTable($table, $field);
		if ($fkt) {
			// the field is a foreign key
			$target_element = Meta::element($fkt);
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
		$element = Meta::element($table);
		$field_type = Meta::type($table, $field);
		$subtype = Meta::subtype($table, $field);
		
		if ($subtype == "checkbox") {
			return "<input type=\"checkbox\" class=\"form-control\" name=\"" . $field . "\" id=\"" . $field . "\" value=\"1\"  {{old(\"" . $field . "\") ? 'checked' : ''}}/>";
		}
		
		if ($subtype == "password_with_confirmation" || $subtype == "password_confirmation") {
			$type = "password";
		}
		
		$fkt = Schema::foreignKeyReferencedTable($table, $field);
		if ($fkt) {
			// the field is a foreign key
			$target_element = Meta::element($fkt);
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
		$element = Meta::element($table);
		$dusk = Meta::dusk($table, $element, "delete");
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
		$element = Meta::element($table);
		$id = $element . '->' . $primary_index;
		$dusk = Meta::dusk($table, $element, "edit");
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
		$subtype = Meta::subtype($table, $field);
		$element = Meta::element($table);
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
		$subtype = Meta::subtype($table, $field);
		
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
		$list = Meta::fillable_fields($table);
		
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
		$list = Meta::fillable_fields($table);
		foreach ($list as $field) {
			if (! Meta::inForm($table, $field)) continue;
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
				'class_name' => Meta::class_name($table),
				'fillable_names' => Meta::fillable_names($table),
				'element' => Meta::element($table),
				'table_field_list' => self::table_field_list($table),
				'form_field_list' => self::form_field_list($table),
				'button_edit' => self::button_edit($table),
				'button_delete' => self::button_delete($table),
				'primary_index' => Schema::primaryIndex($table),
		);
	}
}