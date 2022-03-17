<?php

namespace App\Helpers;

use App\Helpers\MetadataHelper as Meta;

use App\Models\Schema;
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
	static public function field_display (String $table, String $field) {
		$subtype = Meta::subtype($table, $field);
		$element = Meta::element($table);
		
		if ($subtype == "email") {
			return '<A HREF="mailto:{{$' . $element . '->' . $field . '}}">{{$' . $element . '->' . $field . '}}</A>';
		} elseif ($subtype == "checkbox") {
			return '<input type="checkbox" {{ ($' . $element . '->' . $field . ') ? "checked" : "" }}  onclick="return false;" />';
		} elseif (($subtype == "picture")) {
			$route_name = $element . '.picture';
			$id = '$' . $element . '->id';
			$img =  '$' .$element . '->' . $field;
			return "{!! Blade::picture(\"$route_name\", $id, \"$field\", $img) !!}";
		} elseif (($subtype == "file")) {
			$route_name = $element . '.file';
			$id = '$' . $element . '->id';
			$file =  '$' .$element . '->' . $field;
			$label = '"' . __($element . '.' . $field) . '"';
			return "{!! Blade::download(\"$route_name\", $id, \"$field\", $file, $label) !!}";
			return '{!! Blade::download($' . $element . '->' . $field . ', "' . $element . '", "' . $field .'") !!}';
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
		
		if ($subtype == "enumerate") {
			return '{!! Blade::select("' . $field . '", $' . $field . '_list, false, $' . $element  . '->' . $field .') !!}';
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
		
		if ($subtype == "checkbox") {
			return "<input type=\"checkbox\" class=\"form-control\" name=\"" . $field . "\" id=\"" . $field . "\" value=\"1\"  {{old(\"" . $field . "\") ? 'checked' : ''}}/>";
		}
		
		if ($subtype == "enumerate") {
			$options = Meta::field_metadata($table, $field);
			return Blade::select($field, $options['values'], false, '', []);
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
		
		$rules = [];
		if (Schema::required($table, $field))  {
			$rules[] = "'required'";
		}
		
		if ($size = Schema::columnSize($table, $field)) {
			if (($subtype == "picture") || ($subtype == "file")) {
				$size = 2000;
			}
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
		
		if ($subtype == "enumerate") {
			$options = Meta::field_metadata($table, $field);
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
			if (($subtype == "picture") || ($subtype == "file")) {
				$size = 2000;
			}
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

		if ($subtype == "enumerate") {
			$options = Meta::field_metadata($table, $field);
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
			$res = $faker . '->year()';
		} elseif ('double' == $type) {
			$res = $faker . '->randomFloat(2, 0.0, 1000.0)';
		} elseif ('decimal' == $type) {
			$res = $faker . '->randomFloat(2, 0, 100.0)';
		} elseif ('bigint' == $type) {
			$res = $faker . '->randomNumber()';
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
			$res = $faker . '->randomNumber(), // Foreign key to ' . $target_table . ', raises QueryException';
		}
		return $res;
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
				'faker' => self::field_faker($table, $field),
				'display_name' => ucfirst(str_replace('_', ' ',$field))
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
	 * An array of metadata for all fields to display in the forms
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
	 * An array of metadata for all fields relevant to factories
	 * @param String $table
	 * @return string[][]
	 */
	static public function factory_field_list (String $table) {
		$res = [];
		// Todo : it is not fillable_fields
		$list = Meta::fillable_fields($table);
		$list = Schema::fieldList($table);
		foreach ($list as $field) {
			if (in_array($field, ["id", "created_at", "updated_at"])) continue;
			// if (! Meta::inForm($table, $field)) continue;
			// echo "adding $table $field to form\n";
			$res[] = self::field_metadata($table, $field);
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
		foreach ($list as $field) {
			$subtype = Meta::subtype($table, $field);
			
			if ($subtype == "enumerate") {
				$options = Meta::field_metadata($table, $field);
				//var_dump($options);
				$list = '';
				if (array_key_exists("values", $options)) {
					$list = '"'.implode('","', $options['values']).'"';
				}
				$list = '$' . $field . '_list = [' . $list . '];';
				$elt['selector'] = $list;
				$with = '->with("' . $field . '_list", $' . $field .'_list)';
				$elt['with'] = $with;
				// echo "field = $field, subtype=$subtype, list = $list\n";
				// var_dump($elt);
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
			if ($type == $mutating_type) {
				$res[] = ["field" => $field, "field_name" => ucfirst($field)];
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
			}
			if ($line) $res[] = $line;
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
		return array(
				'table' => $table,
				'class_name' => Meta::class_name($table),
				'fillable_names' => Meta::fillable_names($table),
				'element' => Meta::element($table),
				'table_field_list' => self::table_field_list($table),
				'form_field_list' => self::form_field_list($table),
				'factory_field_list' => self::factory_field_list($table),
				'button_edit' => self::button_edit($table),
				'button_delete' => self::button_delete($table),
				'primary_index' => Schema::primaryIndex($table),
				'select_list' => self::select_list($table),
				'id_data_type' => self::id_data_type($table),
				'is_referenced' => Schema::isReferenced($table) ? "true" : "",
				'date_mutators' => self::type_mutators($table, "date"),
				'datetime_mutators' => self::type_mutators($table, "datetime"),
				'picture_url'=> self::picture_file_url($table, "picture"),
				'download_url'=> self::picture_file_url($table, "file"),
				'controller_list'=> self::controller_list($table)
		);
	}
}