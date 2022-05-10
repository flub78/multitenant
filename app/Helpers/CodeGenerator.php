<?php

namespace App\Helpers;

use App\Helpers\MetadataHelper as Meta;
use App\Models\Schema;
use App\Models\ViewSchema;
use Illuminate\Support\Facades\Log;
use App\Helpers\HtmlHelper as HH;
use App\Helpers\BladeHelper as Blade;

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
	
	/**
	 * Generate code to display an element in a table list view
	 *
	 * @param String $table
	 * @param String $field
	 * @return string
	 */
	static public function field_display (String $table, String $field, String $view = "", String $view_field = "") {
		$subtype = Meta::subtype($table, $field);
		$field_type = Meta::type($table, $field);
		
		// echo "field_display ($table, $field), type=$field_type, subtype=$subtype\n";
		if ($view) {
			$element = Meta::element($view);
			$value = '$' .$element . '->' . $view_field;
		} else {
			$element = Meta::element($table);
			$value = '$' .$element . '->' . $field;
		}
		
		if ($subtype == "email") {
			return '<A HREF="mailto:{{' . $value . '}}">{{' . $value . '}}</A>';
		
		} elseif ($subtype == "checkbox") {
			return '<input type="checkbox" {{ (' . $value . ') ? "checked" : "" }}  onclick="return false;" />';
		
		} elseif (($subtype == "enumerate")) {
			$table_field = $element . '.' . $field;
			return "{!! Blade::enumerate(\"$table_field\", $value) !!}";

		} elseif (($subtype == "bitfield")) {
			$table_field = $element . '.' . $field;
			return "{!! Blade::bitfield(\"$table\", \"$field\", $value) !!}";
			
		} elseif (($subtype == "picture")) {
			$route_name = $element . '.picture';
			$id = '$' . $element . '->id';
			return "{!! Blade::picture(\"$route_name\", $id, \"$field\", $value) !!}";
		
		} elseif (($subtype == "file")) {
			$route_name = $element . '.file';
			$id = '$' . $element . '->id';
			$label = '"' . __($element . '.' . $field) . '"';
			return "{!! Blade::download(\"$route_name\", $id, \"$field\", $value, $label) !!}";
		
		} elseif (($subtype == "currency")) {
			return '<div align="right">' . "{!! Blade::currency($value) !!}" . '</div>';
		
		} elseif (in_array($field_type, ['double', 'decimal'])) {
			return '<div align="right">' . "{!! Blade::float($value) !!}" . '</div>';
		}
		
		return '{{' . $value . '}}';
	}
	
	/**
	 * Generate code for a GUI form label
	 *
	 * @param String $table
	 * @param String $field
	 * @return string
	 */
	static public function field_label (String $table, String $field, String $view = "", String $view_field = "") {
		$element = Meta::element($table);
		$subtype = Meta::subtype($table, $field);
		
		if ('bitfield_boxes' == $subtype) $field = substr($field, 0, -6);
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
		
		if ('bitfield_boxes' == $subtype) {
			// return the result for the root field
			$field = substr($field, 0, -6);
			$subtype = 'bitfield';
		}
		
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
		
		if ($subtype == "enumerate") {
			return '{!! Blade::select("' . $field . '", $' . $field . '_list, false, $' . $element  . '->' . $field .') !!}';
		}
		
		if ($subtype == "bitfield") {
			$options = Meta::field_metadata($table, $field);
			
			return '{!! Blade::bitfield_input("' . $table . '", "' . $field . '", $' . $element  . '->' . $field .') !!}';
			
		}
		
		if ($field_type == "text") {
			$options = Meta::field_metadata($table, $field);
			$rows = 4;
			$cols = 40;
			if ($options) {
				if (array_key_exists("rows", $options)) $rows = $options["rows"];
				if (array_key_exists("cols", $options)) $cols = $options["cols"];
			}
			$class = "form-control";
			$txt = '<textarea rows="'. $rows .'" cols="'. $cols . '" class="'. $class .
			'" name="'. $field . '">{{ old("'. $field . '",  $' . $element  . '->' . $field . ') }}</textarea>';
			return $txt;
		}
		
		$class = 'form-control';
		$prefix = "";
		
		if ($field_type == "date") {
			$class .= ' datepicker';
		}
		
		if ($field_type == "time") {
			$class .= ' timepicker';
		}
		
		if ($subtype == "phone") {
			$type = 'tel';
		}		
		
		if ($subtype == "color") {
			$class .= ' colorpicker';
			$type = 'color';
		}
		
		if ($subtype == "file") {
			$type = 'file';
			$prefix = '{{$' . $element . '->' . $field . '}}';
		}
		
		if ($subtype == "picture") {
			$type = 'file';
			$prefix = '{!! Blade::picture("' . $element . '.' . $field . '", $' . $element . '->id' . ', "' . $field . '", $' . $element .  '->'  . $field . ') !!}';
		}
		
		return $prefix . '<input type="' . $type
		. '" class="' . $class .'" name="'
				. $field . '" value="{{ old("' . $field . '", $' . $element . '->' . $field . ') }}"/>';
	}
	
	static function isAssoc($array) {
		return ($array !== array_values($array));
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
		$field_type = Meta::type($table, $field);
		$subtype = Meta::subtype($table, $field);
		$element = Meta::element($table);
		
		if ('bitfield_boxes' == $subtype) {
			// return the result for the root field
			$field = substr($field, 0, -6);
			$subtype = 'bitfield';
		}
		
		if ($subtype == "checkbox") {
			return "<input type=\"checkbox\" class=\"form-control\" name=\"" . $field . "\" id=\"" . $field . "\" value=\"1\"  {{old(\"" . $field . "\") ? 'checked' : ''}}/>";
		}
		
		if ($subtype == "enumerate") {
			$options = Meta::field_metadata($table, $field);
			$values = [];
			foreach ($options['values'] as $value) {
				$values[$value] = '{{__("' . $element . '.' .$field. '.' . $value . '") }}';
			}
			return Blade::select($field, $values, false, '', []);
		}
	
		if ($subtype == "bitfield") {
			$options = Meta::field_metadata($table, $field);
			$values = [];
			foreach ($options['values'] as $value) {
				$values[$value] = '{{__("' . $element . '.' .$field. '.' . $value . '") }}';
			}
			return Blade::radioboxes($table, $field, $values, false, '', []);
		}
		
		$fkt = Schema::foreignKeyReferencedTable($table, $field);
		if ($fkt) {
			// the field is a foreign key
			$target_element = Meta::element($fkt);
			return '{!! Blade::selector("' . $field . '", $' . $target_element . '_list, "") !!}';
		}
		
		if ($field_type == "text") {
			$options = Meta::field_metadata($table, $field);
			$rows = 4;
			$cols = 40;
			if ($options) {
				if (array_key_exists("rows", $options)) $rows = $options["rows"];
				if (array_key_exists("cols", $options)) $cols = $options["cols"];
			}
			
			$class = "form-control";
			$txt = '<textarea rows="'. $rows .'" cols="'. $cols . '" class="'. $class .
			'" name="'. $field . '">{{ old("'. $field . '") }}</textarea>';
			return $txt;
		}
		
		if ($subtype == "password_with_confirmation" || $subtype == "password_confirmation") {
			$type = "password";
		}
		
		$class = 'form-control';
		
		if ($field_type == "date") {
			$class .= ' datepicker';
		}
		
		if ($field_type == "time") {
			$class .= ' timepicker';
		}
		
		if ($subtype == "phone") {
			$type = 'tel';
		}
		
		if ($subtype == "color") {
			$class .= ' colorpicker';
			$type = 'color';
		}

		if (($subtype == "picture") || ($subtype == "file")) {
			$type = 'file';
		}
		
		return HH::input($type, $class, $field);
	}
	
	/**
	 * Generate a delete button for a table
	 *
	 * @param String $table
	 * @return string
	 */
	static public function button_delete (String $table) {
		$element = Meta::element($table);
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

     * @SuppressWarnings("PMD.ShortVariable")
	 */
	static public function button_edit (String $table) {
		$primary_index = Schema::primaryIndex($table);
		$element = Meta::element($table);
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

     * @SuppressWarnings("PMD.ShortVariable")
	 */
	static public function field_rule_edit (String $table, String $field) {
		$subtype = Meta::subtype($table, $field);
		$element = Meta::element($table);
		$primary_index = Schema::primaryIndex($table);
		$options = Meta::field_metadata($table, $field);
		$field_type = Meta::type($table, $field);
		
		$rules = [];
		if (Schema::required($table, $field))  {
			$rules[] = "'required'";
		} else {
			$rules[] = "'nullable'";
		}
		
		if (in_array($field_type, ['year', 'double', 'decimal', 'bigint', 'int'])) {				
			$rules[] = "'numeric'";
		}
		
		if ($options && array_key_exists("min", $options)) {
			$size = $options['min'];
			$rules[] = "'min:$size'";
		}
		
		if ($options && array_key_exists("max", $options)) {
			$size = $options['max'];
			$rules[] = "'max:$size'";
		} elseif ($size = Schema::columnSize($table, $field)) {
			if (($subtype == "picture") || ($subtype == "file")) {
				$size = 2000;
			}
			if (in_array($field_type, ['varchar', 'text'])) {				
				$rules[] = "'max:$size'";
			}
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
		
		if ($subtype == "enumerate") {
			$list = '';
			if (array_key_exists("values", $options)) {
				$list = '"'.implode('","', $options['values']) . '"';
				$rules[] = "Rule::in([$list])";
			}
			if (array_key_exists("rules_to_add", $options)) {
				$rules = array_merge($rules, $options['rules_to_add']);
			}
		}
		
		$tab = str_repeat("\t", 6);
		return  '[' . implode(",\n$tab", $rules) . ']';
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
		$options = Meta::field_metadata($table, $field);
		$field_type = Meta::type($table, $field);
		
		// echo "field_rule_create ($table, $field) type=$field_type subtype=$subtype " . var_export($options, true) . "\n"; 
		
		$rules = [];
		if (Schema::required($table, $field))  {
			$rules[] = "'required'";
		} else {
			$rules[] = "'nullable'";
		}
		
		if (in_array($field_type, ['year', 'double', 'decimal', 'bigint', 'int'])) {
			$rules[] = "'numeric'";
		}
		
		if ($options && array_key_exists("min", $options)) {
			$size = $options['min'];
			$rules[] = "'min:$size'";
		}
		
		if ($options && array_key_exists("max", $options)) {
			$size = $options['max'];
			$rules[] = "'max:$size'";
		} elseif ($size = Schema::columnSize($table, $field)) {
			if (($subtype == "picture") || ($subtype == "file")) {
				$size = 2000;
			}
			if (in_array($field_type, ['varchar', 'text'])) {
				$rules[] = "'max:$size'";
			}
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

		if ($subtype == "enumerate") {
			// var_dump($options);
			$list = '';
			if (array_key_exists("values", $options)) {
				$list = '"'.implode('","', $options['values']) . '"';
				$rules[] = "Rule::in([$list])";
			}
			if (array_key_exists("rules_to_add", $options)) {
				$rules = array_merge($rules, $options['rules_to_add']);
			}
		}
		
		$tab = str_repeat("\t", 6);
		return  '[' . implode(",\n$tab", $rules) . ']';
	}
	
	static public function random_float ($min,$max) {
		return ($min + lcg_value() * (abs($max - $min)));
	}
	
	/**
	 * Faker are used to create random elements for testing
	 *
	 * @param String $table
	 * @param String $field
	 * @return string
	 */
	static public function field_faker (String $table, String $field) {
		$subtype = Meta::subtype($table, $field);
		$type = Meta::type($table, $field);
		$unique = Schema::unique($table, $field);
		$options = Meta::field_metadata($table, $field);
		
		// $faker = ($unique) ? '$this->faker->unique()' : '$this->faker';
		// Tests relies on random elements to be different
		$faker = '$this->faker->unique()';
		
		$res = "$table.$field faker type=$type, subtype=$subtype\n";
		
		if ('varchar' == $type) {
			$res =  "\"$field" . '_" . $next . "_" . ' . 'Str::random()';
		} elseif ('date' == $type) {
			$res = $faker . '->date(__("general.date_format"))';
		} elseif ('datetime' == $type) {
			$res = $faker . '->date(__("general.datetime_format"))';
		} elseif ('time' == $type) {
			$res = $faker . '->time("H:i:s")';
		} elseif ('text' == $type) {
			$res = $faker . '->text(200)';
		} elseif ('year' == $type) {
			$min = ($options && array_key_exists("min", $options)) ? $options['min'] : 1950;
			$max = ($options && array_key_exists("max", $options)) ? $options['max'] : 2020;			
			$res = "rand($min, $max)";
		} elseif ('double' == $type) {
			$min = ($options && array_key_exists("min", $options)) ? $options['min'] : 0.0;
			$max = ($options && array_key_exists("max", $options)) ? $options['max'] : 10000.0;
			$res = $faker . "->randomFloat(2, $min, $max)";
		} elseif ('decimal' == $type) {
			$min = ($options && array_key_exists("min", $options)) ? $options['min'] : 0.0;
			$max = ($options && array_key_exists("max", $options)) ? $options['max'] : 1000.0;
			$res = $faker . "->randomFloat(2, $min, $max)";
		} elseif ('bigint' == $type) {
			$min = ($options && array_key_exists("min", $options)) ? $options['min'] : 0;
			$max = ($options && array_key_exists("max", $options)) ? $options['max'] : 10000;
			$res = "rand($min, $max)";
		}
		
		if ('email' == $subtype) {
			$res = $faker . '->safeEmail()';
			
		} elseif ('checkbox' == $subtype) {
			$res = $faker . '->boolean()';
			
		} elseif ('enumerate' == $subtype) {
			$values = Meta::field_metadata($table, $field)["values"];
			$list = '["'.implode('","', $values) . '"]';
			$res = $faker . '->randomElement(' . $list .')';
			
		} elseif ('color' == $subtype) {
			$res = $faker . '->hexcolor()';
			
		} elseif ('foreign_key' == $subtype) {
			$target_table = Schema::foreignKeyReferencedTable ($table, $field);
			$target_class = Meta::class_name($target_table);
			$res = $target_class . '::factory()->create()->getKey()';
		}
		return $res;
	}

	/**
	 * Generation of the database migration
	 *
	 * @param String $table
	 * @param String $field
	 * @return string
	 * 
	 * TODO CHeck that all types of integers are supported
	 * 
	 */
	static public function field_migration (String $table, String $field) {
		$subtype = Meta::subtype($table, $field);
		$type = Meta::type($table, $field);
		$unique = Schema::unique($table, $field);
		$options = Meta::field_metadata($table, $field);
		$comment = Schema::columnComment ($table, $field);
		$unsigned = Schema::unsignedType ($table, $field);
		
		$res = '$table';
		$json = [];
		
		if ('id' == $field) {
			$res .= "->id();";
			return $res;
		}
			
		if ('varchar' == $type) {
			$size = Schema::columnSize($table, $field);
			$res .= "->string('$field', $size)";			
		} elseif ('date' == $type) {
			$res .= "->date('$field')";
		} elseif ('datetime' == $type) {
			$res .= "->datetime('$field')";
		} elseif ('time' == $type) {
			$res .= "->time('$field')";
		} elseif ('text' == $type) {
			$res .= "->text('$field')";
		} elseif ('year' == $type) {
			$res .= "->year('$field')";
		} elseif ('double' == $type) {
			$res .= "->double('$field')";
		} elseif ('decimal' == $type) {
			$res .= "->float('$field')";
		} elseif ('bigint' == $type) {
			if ($unsigned) 
				$res .= "->unsignedBigInteger('$field')";
			else
				$res .= "->bigInteger('$field')";
		}
		
		if (!Schema::required($table, $field))  {
			$res .= '->nullable()';
		} 

		// all checkboxes are tinyint but all tinyint are not booleans
		if ($subtype == 'checkbox') {
			$res .= "->boolean('$field')";
		}
		
		if ($comment) {
			$res .= "->comment('$comment')";
		}
		
		$res .= ';';
		return $res;
	}
	
	/**
	 * Metadata all metadata for an individual field
	 *
	 * @param String $table
	 * @param String $field
	 * @return Array[]
	 */
	static public function field_metadata(String $table, String $field, String $view = "", String $view_field = "") {
		
		$subtype = Meta::subtype($table, $field);
		if ('bitfield_boxes' == $subtype) $field = substr($field, 0, -6);
		$element = Meta::element($table);
		
		$res = ['name' => $field,
				'display' => self::field_display($table, $field, $view, $view_field),
				'label' => self::field_label($table, $field, $view, $view_field),
				'input_edit' => self::field_input_edit($table, $field),
				'input_create' => self::field_input_create($table, $field),
				'rule_edit' => self::field_rule_edit($table, $field),
				'rule_create' => self::field_rule_create($table, $field),
				'faker' => self::field_faker($table, $field),
				'display_name' => ucfirst(str_replace('_', ' ',$field)),
				'element_name' => $element . '.' . $field,
				'migration' => self::field_migration($table, $field)
		];
		
		if ($view) $res['element'] = $element;
		
		return $res;
	}
	
	/**
	 * An array of metadata for all fillable fields
	 * 
	 * @param String $table
	 * @return string[][]
	 */
	static public function table_field_list (String $table) {
		$res = [];
		$list = Schema::fieldList($table);
		foreach ($list as $field) {
			$res[] = self::field_metadata($table, $field);
		}		
		return $res;
	}
	
	/**
	 * An array of metadata for all fillable fields
	 *
	 * @param String $table
	 * @return string[][]
	 */
	static public function index_field_list (String $table) {
		$res = [];
		$list = Meta::fillable_fields($table);
		$view_def = ViewSchema::isView($table);		// is it a MySQL view ?
		
		if ($view_def) {
			$view_list = ViewSchema::ScanViewDefinition($view_def);
			foreach ($view_list as $view_field) {
				$res[] = self::field_metadata($view_field['table'], $view_field['field'], $table, $view_field['name']);
			}
		} else {
			if ($table == "users") {
				// Todo store this information in database
				$list = ["name", "email", "admin", "active"];
			}
			
			// Fill the result and compute an index
			foreach ($list as $field) {
				$res[] = self::field_metadata($table, $field);
			}
		}
		
		return $res;
	}
	
	/**
	 * An array of metadata for all fields to display in the forms
	 * @param String $table
	 * @return string[][]
	 */
	static public function form_field_list (String $table) {
		$res = [];
		$list = Meta::form_fields($table);
		foreach ($list as $field) {
			if (! Meta::inForm($table, $field)) continue;
			// echo "adding $table $field to form\n";
			$res[] = self::field_metadata($table, $field);
		}
		return $res;
	}
	
	/**
	 * An array of metadata for all fields relevant to factories
	 * @param String $table
	 * @return string[][]
	 */
	static public function factory_field_list (String $table) {
		$res = [];
		$view_def = ViewSchema::isView($table);
		
		if ($view_def) {
			$view_list = ViewSchema::ScanViewDefinition($view_def);
			foreach ($view_list as $view_field) {
				$res[] = self::field_metadata($view_field['table'], $view_field['field'], $table, $view_field['name']);
			}
		} else {
			$list = Schema::fieldList($table);
			foreach ($list as $field) {
				if (in_array($field, ["id", "created_at", "updated_at"])) continue;
				// if (! Meta::inForm($table, $field)) continue;
				// echo "adding $table $field to form\n";
				$res[] = self::field_metadata($table, $field);
			}
		}
		return $res;
	}
	
	/**
	 * Information to generate the selectors
	 * a list of hash table with selector and with keys
	 *	$res[] = ['selector' => '$key_list = ["app.locale", "app.timezone"];',
	 *			'with' => '->with ("key_list", $key_list)'
	 *	];
	 * @param String $table
	 * @return string[]
	 */
	static public function select_list (String $table) {
		$res = [];
		$list = Meta::fillable_fields($table);
		$element = Meta::element($table);
		foreach ($list as $field) {
			$subtype = Meta::subtype($table, $field);
			
			if ($subtype == "enumerate") {
				$options = Meta::field_metadata($table, $field);

				$list = '';
				if (array_key_exists("values", $options)) {
					$count = count($options['values']);
					$elt_field = $element . '.' . $field;
					$cnt = 0;
					foreach ($options['values'] as $value) {
						$lang_id = $elt_field . '.' . $value;
						$list .= "\"$value\" => __(\"$lang_id\")";
						$cnt++;
						if ($cnt < $count) $list .= ",\n        		";
					}
				}
				$list = '$' . $field . '_list = [' . $list . '];';
				$elt['selector'] = $list;
				
				$with = '->with("' . $field . '_list", $' . $field .'_list)';
				$elt['with'] = $with;
				
				// echo "field = $field, subtype=$subtype, list = $list\n";
				// var_dump($elt);
				$res[] = $elt;
				
			} elseif ('foreign_key' == $subtype) {
				
				$target_table = Schema::foreignKeyReferencedTable ($table, $field);
				$target_class = Meta::class_name($target_table);
				$element = Meta::element($target_table);
				$list = $element . '_list';
				
				$elt['selector'] = "\$$list = $target_class::selector();"; 
				$elt['with'] = "\n\t\t\t->with('$list', \$$list)";
				$res[] = $elt;
			}
		}
		return $res;
	}
	
	/**
	 * Generate code for model when the key type is not integer
	 * @param String $table
	 */
	static public function id_data_type (String $table) {
		
		$type = "string";
		$primary = Schema::primaryIndex($table);
		$primary_type = Meta::type($table, $primary); 
				
		if ($primary_type == "varchar") {
			return "    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected \$keyType = '$type';
";
		}
		return "";
	}
	
	/**
	 * An array
	 * @param String $table
	 * @param String mutating_type
	 * @return string[][]
	 */
	static public function type_mutators (String $table, String $mutating_type) {
		$res = [];
		$list = Meta::fillable_fields($table);
		$list = Schema::fieldList($table);
		foreach ($list as $field) {
			$type = Meta::type($table, $field);
			$subtype = Meta::subtype($table, $field);
			if ($type == $mutating_type || $subtype == $mutating_type) {
				$res[] = ["field" => $field, "field_name" => ucfirst($field)];
			} elseif ("float" == $mutating_type && "currency" != $subtype) {
				if (in_array($type, ['double', 'decimal'])) {
					$res[] = ["field" => $field, "field_name" => ucfirst($field)];
				}
			}
		}
		return $res;
	}
	
	/**
	 * Generate the information for the picture and download methods of the controllers
	 * picture and download methods are only required when there are picture of file fields in a table
	 * @param unknown $table
	 * @param unknown $subtype
	 * @return mixed[][]|string[][]
	 */
	static public function picture_file_url($table, $subtype) {
		$res = [];		
		$picture = false;
		foreach (Schema::fieldList($table) as $field) {
			if ($subtype == Meta::subtype($table, $field)) {
				$picture = true;
				break;
			}
		}
		if ($picture) $res[] = ['class_name' => Meta::class_name($table)];
		return $res;
	}

	/**
	 * General information to supplement the store, update and destroy methods of the controller 
	 * @param unknown $table
	 * @return string[][]
	 */
	static public function controller_list($table) {
		$res = [];
		$element = Meta::element($table);
		foreach (Schema::fieldList($table) as $field) {
			$subtype = Meta::subtype($table, $field);
			$field_type = Meta::type($table, $field);
			
			$line = [];
			
			if ("date" == $field_type) {
				$line["update"] = "\$this->update_date(\$validatedData, '$field');";
			} elseif ("datetime_with_date_and_time" == $subtype) {
				$line["store"] = "\$this->store_$subtype(\$validatedData, '$field');";
				$line["update"] = "\$this->update_$subtype(\$validatedData, '$field');";
			} elseif ("picture" == $subtype || "file" == $subtype) {
				$line["store"] = "\$this->store_$subtype(\$validatedData, \"$field\", \$request, \"$element\");";
				$line["destroy"] = "if (\$$element->$field) \$this->destroy_file( \$$element->$field);";
				$line["update"] = "\$this->update_$subtype(\$validatedData, \"$field\", \$request, \"$element\", \$previous);";
			} elseif ("bitfield" == $subtype) {
				$line["store"] = "\$this->store_$subtype(\$validatedData, \"$field\", \$request, \"$element\");";
				$line["update"] = "\$this->update_$subtype(\$validatedData, \"$field\", \$request, \"$element\");";
			}
			if ($line) $res[] = $line;
		}
		return $res;
	}
	
	/**
	 * List of the enumerate for translation
	 * 
	 * @param unknown $table
	 * @return string[][]
	 */
	static public function enumerate_list($table) {
		$res = [];
		// $element = Meta::element($table);
		foreach (Schema::fieldList($table) as $field) {
			$subtype = Meta::subtype($table, $field);
			// $field_type = Meta::type($table, $field);
			$options = Meta::field_metadata($table, $field);
			
			if ($subtype == "enumerate" || $subtype == "bitfield" ) {
				
				foreach ($options['values'] as $value) {
					$line = [];
					$line["name"] = $field . "." . $value;
					$line["display_name"] = ucfirst(str_replace('_', ' ',$value));
					if ($line) $res[] = $line;
				}
			}
		}
		return $res;
	}
	
	/**
	 * Generate foreign keys instruction for migrations
	 * 
	 * @param String $table
	 */
	static public function foreign_keys($table) {
		$res = "";
		
		foreach (Schema::fieldList($table) as $field) {
			if (Schema::foreignKey($table, $field)) {
				$foreign_table = Schema::foreignKeyReferencedTable ($table, $field);
				$foreign_field = Schema::foreignKeyReferencedColumn ($table, $field);
				$res .= "\n\t\t\t";
				$res .= "\$table->foreign('$field')->references('$foreign_field')->on('$foreign_table')";
				
				// Fetch the type: restrict | cascade | no action from the database ...
				$delete_rule =  Schema::onDeleteConstraint($table, $field);
				$update_rule = Schema::onUpdateConstraint($table, $field);
				
				$res .= "->onUpdate('$update_rule')->onDelete('$delete_rule');";
			}
		}
		return $res;
	}
	
	/**
	 * Return a list of lines of the type
	 * use App\Models\User;
	 *
	 * @param String $table
	 */
	static public function factory_referenced_models($table) {
		$res = '';
		foreach (Schema::fieldList($table) as $field) {
			if (Schema::foreignKey($table, $field)) {
				$foreign_table = Schema::foreignKeyReferencedTable ($table, $field);
				$foreign_class = Meta::class_name($foreign_table);
				if ("User" == $foreign_class) {
					$res .= "use App\Models\User;\n";
				} else {
					$res .= "use App\Models\Tenants\\$foreign_class;\n";
				}
			}
		}
		return $res;
	}
	
	/**
	 * All the information for mustache engine
	 *
	 * @param String $table
	 * @return array[]
	 */
	static public function metadata(String $table) {
		$is_view = ViewSchema::isView($table);
		// ($is_view) ? '' : 
		return array(
				'table' => $table,
				'class_name' => Meta::class_name($table),
				'fillable_names' => Meta::fillable_names($table),
				'element' => Meta::element($table),
				'table_field_list' => self::table_field_list($table),
				'form_field_list' => self::form_field_list($table),
				'index_field_list' => self::index_field_list($table),
				'factory_field_list' => self::factory_field_list($table),
				'button_edit' => self::button_edit($table),
				'button_delete' => self::button_delete($table),
				'primary_index' => Schema::primaryIndex($table),
				'select_list' => self::select_list($table),
				'id_data_type' => self::id_data_type($table),
				'is_referenced' => (!$is_view && Schema::isReferenced($table)) ? "true" : "",
				'date_mutators' => ($is_view) ? '' : self::type_mutators($table, "date"),
				'datetime_mutators' => ($is_view) ? '' : self::type_mutators($table, "datetime"),
				'currency_mutators' => ($is_view) ? '' : self::type_mutators($table, "undefined_currency"),
				'float_mutators' => ($is_view) ? '' : self::type_mutators($table, "undefined_float"),
				'picture_url'=> self::picture_file_url($table, "picture"),
				'download_url'=> self::picture_file_url($table, "file"),
				'controller_list'=> self::controller_list($table),
				'enumerate_list' => self::enumerate_list($table),
				'is_view' => $is_view,
				'foreign_keys' => self::foreign_keys($table),
				'factory_referenced_models' => self::factory_referenced_models($table)
		);
	}
}