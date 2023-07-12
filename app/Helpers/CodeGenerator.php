<?php

namespace App\Helpers;

use App\Helpers\MetadataHelper as Meta;
use App\Models\Schema;
use App\Models\ViewSchema;
use Illuminate\Support\Facades\Log;
use App\Helpers\HtmlHelper as HH;
use App\Helpers\BladeHelper as Blade;

/**
 * Code Generator
 *
 * This helper generate code from the metadata information.
 *
 * @author frederic
 *
 */
class CodeGenerator {

    // ###############################################################################################################
    // Code Generation
    // ###############################################################################################################


    /**
     * Transform an underscore separated string into camel case
     *
     * @param unknown $string
     * @param boolean $capitalizeFirstCharacter
     * @return mixed
     */
    static public function toCamelCase($string, $capitalizeFirstCharacter = true) {

        $str = str_replace('_', ' ', $string);
        $str = ucwords($str);
        $str = str_replace(' ', '', $str);

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }

    /**
     * return a string at the format '["elt1", "elt2"]' from an array of string
     * 
     * @param unknown $array
     */
    static public function array_to_string($array) {
        $res = '[';
        if ($array) {
            $res .= '"' . implode('", "', $array) . '"';
        }
        $res .= ']';
        return $res;
    }

    /**
     * Generate a dusk anchor
     * @param String $table
     * @param String $element
     * @param String $type
     * @return string
     */
    static public function dusk(String $table, String $element, String $type = "edit") {

        $dusk_field = ($table == "users") ? "name" : Schema::primaryIndex($table);
        if ($type == "edit") {
            return 'edit_{{ $' . $element . '->' . $dusk_field . ' }}';
        } else {
            return 'delete_{{ $' . $element . '->' . $dusk_field . ' }}';
        }
    }

    /**
     * Check if a field can contain a lot of different values and so
     * use a different one for every object instance for testins.
     * By opposition booleans or small enumerates cannot support that.
     *
     * @param unknown $table
     * @param unknown $element
     */
    static public function lot_of_values(String $table, String $field) {
        $subtype = Meta::subtype($table, $field);
        if (in_array($subtype, ['checkbox', 'enumerate'])) {
            return false;
        }
        return true;
    }


    /**
     * Generate code to display an element in a table list view
     *
     * @param String $table
     * @param String $field
     * @return string
     */
    static public function field_display(String $table, String $field, String $view = "", String $view_field = "") {
        $subtype = Meta::subtype($table, $field);
        $field_type = Meta::type($table, $field);

        if ($view) {
            $element = Meta::element($view);
            $value = '$' . $element . '->' . $view_field;
        } else {
            $element = Meta::element($table);
            $value = '$' . $element . '->' . $field;
        }

        if ($field_type == "date") {
            return '{{DateFormat::to_local_date(' . $value . ')}}';
        } elseif ($field_type == "datetime") {
            return '{{DateFormat::local_datetime(' . $value . ')}}';
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
            $options = Meta::field_metadata($table, $field);
            if (!array_key_exists("values", $options)) return $value;
            $str_values = self::array_to_string($options['values']);
            return "{!! Blade::bitfield(\"$table\", \"$field\", $value, \"$element\", $str_values) !!}";
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
        } elseif (($subtype == "foreign_key")) {
            $value = '$' . $element . '->';
            $method = str_replace('_id', '', $field) . "_image()";
            $value .= $method;
            return '{{' . $value . '}}';
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
    static public function field_label(String $table, String $field, String $view = "", String $view_field = "",) {
        $element = Meta::element($table);
        $subtype = Meta::subtype($table, $field);
        $class = "form-label";

        if ('bitfield_boxes' == $subtype) $field = substr($field, 0, -6);

        if ($subtype == "file" || $subtype == "picture") {
            // $class .= " mt-4";
        }

        if ($subtype == "checkbox") {
            $class .= " m-2";
        }

        return '<label class="' . $class .  '" for="' . $field . '">{{__("' . $element . '.' . $field . '")}}</label>';
    }

    /**
     * Generate code for input in an edit form
     *
     * @param String $table
     * @param String $field
     * @return string
     */
    static public function field_input_edit(String $table, String $field) {
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
            return "<input type=\"checkbox\" class=\"form-check-input m-2\" name=\"" . $field . "\" value=\"1\"  {{old(\"" . $field . "\", $" . $element . "->" . $field . ") ? 'checked' : ''}}/>";
        }

        if ($subtype == "password_with_confirmation" || $subtype == "password_confirmation") {
            $type = "password";
            return '<input type="' . $type . '" class="form-control" name="' . $field . '" value="{{ old("' . $field . '") }}"/>';
        }

        $fkt = Schema::foreignKeyReferencedTable($table, $field);
        if ($fkt) {
            // the field is a foreign key
            $target_element = Meta::element($fkt);
            return '{!! Blade::selector("' . $field . '", $' . $target_element . '_list, $' . $element  . '->' . $field . ') !!}';
        }

        if ($subtype == "enumerate") {
            return '{!! Blade::select("' . $field . '", $' . $field . '_list, false, $' . $element  . '->' . $field . ') !!}';
        }

        if ($subtype == "bitfield") {
            $options = Meta::field_metadata($table, $field);
            if (!array_key_exists("values", $options)) return $value;
            $str_values = self::array_to_string($options['values']);

            return '{!! Blade::bitfield_input("' . $table . '", "' . $field . '", $' . $element  . '->' . $field
                . ", \"$element\",  $str_values"
                . ') !!}';
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
            $txt = '<textarea rows="' . $rows . '" cols="' . $cols . '" class="' . $class .
                '" name="' . $field . '">{{ old("' . $field . '",  $' . $element  . '->' . $field . ') }}</textarea>';
            return $txt;
        }

        $class = 'form-control';
        $prefix = "";

        if ($field_type == "date") {
            // $class .= ' datepicker';
            $type = 'date';
        }

        if ($field_type == "time") {
            // $class .= ' timepicker';
            $type = 'time';
        }

        if ($field_type == "datetime") {
            $type = "datetime-local";
        }

        if ($subtype == "phone") {
            $type = 'tel';
        }

        if ($subtype == "color") {
            // $class .= ' colorpicker';
            $type = 'color';
        }

        if ($subtype == "file") {
            $type = 'file';
        }

        if ($subtype == "picture") {
            $type = 'file';
        }

        return $prefix . '<input type="' . $type
            . '" class="' . $class . '" name="'
            . $field . '" value="{{ old("' . $field . '", $' . $element . '->' . $field . ') }}"/>';
    }

    /**
     * Generate code for input in an edit form
     *
     * @param String $table
     * @param String $field
     * @return string
     */
    static public function field_label_input_edit(String $table, String $field) {
        $type = "text";
        $element = Meta::element($table);
        $field_type = Meta::type($table, $field);
        $subtype = Meta::subtype($table, $field);

        if ($subtype == "file" || $subtype == "picture") {
            if ($subtype == "file") {
                $prefix = '{{$' . $element . '->' . $field . '}}' . "\n";
            }

            if ($subtype == "picture") {
                $prefix = '{!! Blade::picture("' . $element . '.' . $field . '", $' .
                    $element . '->id' . ', "' . $field . '", $' .
                    $element .  '->'  . $field . ') !!}' . "\n";
            }


            $res = '<div class="d-flex flex-wrap">' . "\n";
            $res .= '              <div class="form-floating mb-2 border">' . "\n";
            $res .= '                  ' . self::field_input_edit($table, $field) . "\n";
            $res .= '                  ' . self::field_label($table, $field) . "\n";
            $res .= '              </div>' . "\n";

            $res .= '              <div class="m-2">' . "\n";
            $res .= '                  ' . $prefix . "\n";
            $res .= '              </div>' . "\n";

            $res .= '          </div>' . "\n";
            return $res;
        }

        if ($subtype == "checkbox") {
            $res = '<div class="form-group mb-2 border">' . "\n";
            $res .= '              ' . self::field_label($table, $field) . "\n";
            $res .= '              ' . self::field_input_edit($table, $field) . "\n";
            $res .= '          </div>' . "\n";
            return $res;
        }

        $res = '<div class="form-floating mb-2 border">' . "\n";
        $res .= '              ' . self::field_input_edit($table, $field) . "\n";
        $res .= '              ' . self::field_label($table, $field) . "\n";
        $res .= '          </div>' . "\n";
        return $res;
    }

    /**
     * Generate code for input in a create form
     *
     * @param String $table
     * @param String $field
     * @return string
     */
    static public function field_label_input_create(String $table, String $field) {
        $type = "text";
        $element = Meta::element($table);
        $field_type = Meta::type($table, $field);
        $subtype = Meta::subtype($table, $field);

        if ($subtype == "checkbox") {

            $res = '<div class="form-group mb-2 border">' . "\n";
            $res .= '              ' . self::field_label($table, $field) . "\n";
            $res .= '              ' . self::field_input_create($table, $field) . "\n";
            $res .= '          </div>' . "\n";
            return $res;
        }

        $res = '<div class="form-floating mb-2 border">' . "\n";
        $res .= '              ' . self::field_input_create($table, $field) . "\n";
        $res .= '              ' . self::field_label($table, $field) . "\n";
        $res .= '          </div>' . "\n";
        return $res;
    }

    /**
     * helper function to know if an array is associative
     *
     * @param unknown $array
     * @return boolean
     */
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
    static public function field_input_create(String $table, String $field) {
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
            return "<input type=\"checkbox\" class=\"form-check-input m-2\" name=\"" . $field . "\" id=\"" . $field . "\" value=\"1\"  {{old(\"" . $field . "\") ? 'checked' : ''}}/>";
        }

        if ($subtype == "enumerate") {
            $options = Meta::field_metadata($table, $field);
            $values = [];
            foreach ($options['values'] as $value) {
                $values[$value] = '{{__("' . $element . '.' . $field . '.' . $value . '") }}';
            }
            return Blade::select($field, $values, false, '', []);
        }

        if ($subtype == "bitfield") {
            $options = Meta::field_metadata($table, $field);
            $values = [];
            foreach ($options['values'] as $value) {
                $values[$value] = '{{__("' . $element . '.' . $field . '.' . $value . '") }}';
            }
            return Blade::radioboxes($table, $field, $values, 0, $element);
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
            $txt = '<textarea rows="' . $rows . '" cols="' . $cols . '" class="' . $class .
                '" name="' . $field . '">{{ old("' . $field . '") }}</textarea>';
            return $txt;
        }

        if ($subtype == "password_with_confirmation" || $subtype == "password_confirmation") {
            $type = "password";
        }

        $class = 'form-control';

        if ($field_type == "date") {
            // $class .= ' datepicker';  no more used with HTML 5
            $type = 'date';
        }

        if ($field_type == "time") {
            // $class .= ' timepicker';
            $type = "time";
        }

        if ($field_type == "datetime") {
            $type = "datetime-local";
        }

        if ($subtype == "phone") {
            $type = 'tel';
        }

        if ($subtype == "color") {
            // $class .= ' colorpicker';
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
    static public function button_delete(String $table) {
        $element = Meta::element($table);
        $dusk = self::dusk($table, $element, "delete");
        $primary_index = Schema::primaryIndex($table);


        $res = '<form action="{{ route("' . $element . '.destroy", $' . $element . '->'
            . $primary_index . ')}}" method="post">' . "\n";
        $res .= "                   @csrf\n";
        $res .= "                   @method('DELETE')\n";
        $res .= "                   <button class=\"btn btn-danger\" type=\"submit\" dusk=\"$dusk\">";
        $res .= '<i class="fa-solid fa-trash"></i>';
        $res .= "</button>\n";
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
    static public function button_edit(String $table) {
        $primary_index = Schema::primaryIndex($table);
        $element = Meta::element($table);
        $id = $element . '->' . $primary_index;
        $dusk = self::dusk($table, $element, "edit");
        $route = "{{ route('$element.edit', \$$id) }}";
        // $label = "{{ __('general.edit') }}";
        $label = '<i class="fa-solid fa-pen-to-square"></i>';
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
    static public function field_rule_edit(String $table, String $field) {
        $subtype = Meta::subtype($table, $field);
        $element = Meta::element($table);
        $primary_index = Schema::primaryIndex($table);
        $options = Meta::field_metadata($table, $field);
        $field_type = Meta::type($table, $field);

        $rules = [];
        if ($subtype != "checkbox") {
            if (Schema::required($table, $field)) {
                $rules[] = "'required'";
            } else {
                $rules[] = "'nullable'";
            }
        } else {
            $rules[] = "''";
        }

        if (in_array($field_type, ['year', 'double', 'decimal', 'bigint', 'int'])) {
            if ($subtype != "bitfield") $rules[] = "'numeric'";
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

        if ($subtype == 'picture') {
            $rules[] = "'mimes:jpeg,bmp,png'";
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
                $list = '"' . implode('","', $options['values']) . '"';
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
    static public function field_rule_create(String $table, String $field) {
        $subtype = Meta::subtype($table, $field);
        $options = Meta::field_metadata($table, $field);
        $field_type = Meta::type($table, $field);

        $rules = [];
        if ($subtype != "checkbox") {
            if (Schema::required($table, $field)) {
                $rules[] = "'required'";
            } else {
                $rules[] = "'nullable'";
            }
        } else {
            $rules[] = "''";
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

        if ($subtype == 'picture') {
            $rules[] = "'mimes:jpeg,bmp,png'";
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
            $list = '';
            if (array_key_exists("values", $options)) {
                $list = '"' . implode('","', $options['values']) . '"';
                $rules[] = "Rule::in([$list])";
            }
            if (array_key_exists("rules_to_add", $options)) {
                $rules = array_merge($rules, $options['rules_to_add']);
            }
        }

        $tab = str_repeat("\t", 6);
        return  '[' . implode(",\n$tab", $rules) . ']';
    }

    static public function random_float($min, $max) {
        return ($min + lcg_value() * (abs($max - $min)));
    }

    /**
     * Faker are used to create random elements for testing
     *
     * @param String $table
     * @param String $field
     * @return string
     */
    static public function field_faker(String $table, String $field) {
        $subtype = Meta::subtype($table, $field);
        $type = Meta::type($table, $field);
        // $unique = Schema::unique($table, $field);
        $options = Meta::field_metadata($table, $field);

        // $faker = ($unique) ? '$this->faker->unique()' : '$this->faker';
        // Tests relies on random elements to be different
        $faker = '$this->faker';

        $not_unique_subtypes = ['checkbox', 'enumerate', 'picture', 'file'];
        if (!in_array($subtype, $not_unique_subtypes)) {
            $faker .= '->unique()';
        }

        $res = "$table.$field faker type=$type, subtype=$subtype\n";

        if ('varchar' == $type) {
            $res =  "\"$field" . '_" . $next . "_" . ' . 'Str::random()';
        } elseif ('date' == $type) {
            $res = $faker . '->date(__("general.database_date_format"))';
        } elseif ('datetime' == $type) {
            $res = $faker . '->date(__("general.database_datetime_format"))';
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
            $list = '["' . implode('","', $values) . '"]';
            $res = $faker . '->randomElement(' . $list . ')';
        } elseif ('color' == $subtype) {
            $res = $faker . '->hexcolor()';
        } elseif ('picture' == $subtype) {
            $res = "\$file = UploadedFile::fake()->image('$field.jpg')";
        } elseif ('file' == $subtype) {
            $res = "\$file = UploadedFile::fake()->image('$field.jpg')";
            $sizeInKb = 3;
            $res = "\$file = UploadedFile::fake()->create('$field.pdf', $sizeInKb)->store('$field.pdf')";
        } elseif ('foreign_key' == $subtype) {
            $target_table = Schema::foreignKeyReferencedTable($table, $field);
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
    static public function field_migration(String $table, String $field) {
        $subtype = Meta::subtype($table, $field);
        $type = Meta::type($table, $field);
        $unique = Schema::unique($table, $field);
        $options = Meta::field_metadata($table, $field);
        $comment = Schema::columnComment($table, $field);
        $unsigned = Schema::unsignedType($table, $field);

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

        if (!Schema::required($table, $field)) {
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
     * Generation of a field default value
     *
     * @param String $table
     * @param String $field
     * @return string
     *
     */
    static public function field_default(String $table, String $field) {

        $subtype = Meta::subtype($table, $field);
        $type = Meta::type($table, $field);
                $res = '';
        $info = Schema::columnInformation($table, $field);
        if (!$info->Default) return "";
       
        $res = '$data["' . $field . '"] = "' . $info->Default . '";';
        
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
        if ('bitfield_boxes' == $subtype) $field = substr($field, 0, -6);  // remove '_boxes'
        $element = Meta::element($table);

        $res = [
            'name' => $field,
            'display' => self::field_display($table, $field, $view, $view_field),
            'label' => self::field_label($table, $field, $view, $view_field),
            //'input_create' => self::field_input_create($table, $field),
            'label_input_edit' => self::field_label_input_edit($table, $field),
            'label_input_create' => self::field_label_input_create($table, $field),
            'rule_edit' => self::field_rule_edit($table, $field),
            'rule_create' => self::field_rule_create($table, $field),
            'faker' => self::field_faker($table, $field),
            'display_name' => ucfirst(str_replace('_', ' ', $field)),
            'element_name' => $element . '.' . $field,
            'migration' => self::field_migration($table, $field),
            'default' => self::field_default($table, $field),
        ];

        if ($view) $res['element'] = $element;

        return $res;
    }


    /**
     * Some subtypes are more difficult to test
     */
    static public function testable(String $table, String $field) {
        $subtype = Meta::subtype($table, $field);
        if (in_array($subtype, ["picture"])) return false;
        return true;
    }

    /**
     * An array of metadata for all fillable fields
     *
     * @param String $table
     * @return string[][]
     */
    static public function table_field_list(String $table) {
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
    static public function index_field_list(String $table) {
        $res = [];
        $list = Meta::fillable_fields($table);
        $view_def = ViewSchema::isView($table);        // is it a MySQL view ?

        if ($view_def) {
            $view_list = ViewSchema::ScanViewDefinition($view_def);
            foreach ($view_list as $view_field) {
                if (!Meta::inTable($table, $view_field['field'])) continue;
                $res[] = self::field_metadata($view_field['table'], $view_field['field'], $table, $view_field['name']);
            }
        } else {
            if ($table == "users") {
                // Todo store this information in database
                $list = ["name", "email", "admin", "active"];
            }

            // Fill the result and compute an index
            foreach ($list as $field) {
                if (!Meta::inTable($table, $field)) continue;
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
    static public function form_field_list(String $table, $for_rules = false) {
        $res = [];
        $list = Meta::form_fields($table);
        foreach ($list as $field) {
            if (!Meta::inForm($table, $field)) continue;

            $meta = self::field_metadata($table, $field);
            $meta['name'] = $field;
            $res[] = $meta;

            $subtype = Meta::subtype($table, $field);

            if ($for_rules && "bitfield_boxes" == $subtype) {
                $basename = substr($field, 0, -6);

                $meta = [];
                $meta['name'] = $basename;
                $meta['rule_edit'] = "['nullable', 'numeric']";
                $meta['rule_create'] = "['nullable', 'numeric']";
                $res[] = $meta;
            }
        }
        return $res;
    }

    /**
     * An array of metadata for all fields relevant to factories
     *
     * @param String $table
     * @return string[][]
     */
    static public function factory_field_list(String $table) {
        $res = [];
        $view_def = ViewSchema::isView($table);

        if ($view_def) {
            // Does it really make sense to generate a factory for a view ?
            $view_list = ViewSchema::ScanViewDefinition($view_def);
            foreach ($view_list as $view_field) {
                $res[] = self::field_metadata($view_field['table'], $view_field['field'], $table, $view_field['name']);
            }
        } else {
            $list = Schema::fieldList($table);
            foreach ($list as $field) {
                if (in_array($field, ["id", "created_at", "updated_at"])) continue;
                $res[] = self::field_metadata($table, $field);
            }
        }
        return $res;
    }

    /**
     * An array of metadata for all fields with default
     *
     * @param String $table
     * @return string[][]
     */
    static public function default_field_list(String $table) {
        $res = [];

        $list = Schema::fieldList($table);
        foreach ($list as $field) {
            if (in_array($field, ["id", "created_at", "updated_at"])) continue;
            $meta = self::field_metadata($table, $field);
            if ($meta['default']) $res[] = $meta;
        }
        return $res;
    }

    /**
     * An array of metadata for all fields used in filter
     *
     * @param String $table
     * @return string[][]
     */
    static public function filter_field_list(String $table) {
        $fff = self::form_field_list($table);
        $res = [];
        foreach ($fff as $elt) {

            if (Meta::in_filter($table, $elt['name'])) {
                $res[] = $elt;
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
    static public function select_list(String $table) {
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

                $with = '->with("' . $field . '_list", $' . $field . '_list)';
                $elt['with'] = $with;

                $res[] = $elt;
            } elseif ('foreign_key' == $subtype) {

                $target_table = Schema::foreignKeyReferencedTable($table, $field);
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
    static public function id_data_type(String $table) {

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
    static public function type_mutators(String $table, String $mutating_type) {
        $res = [];
        $list = Meta::fillable_fields($table);
        $list = Schema::fieldList($table);

        foreach ($list as $field) {
            $type = Meta::type($table, $field);
            $subtype = Meta::subtype($table, $field);

            if ($type == $mutating_type || $subtype == $mutating_type) {
                $res[] = ["field" => $field, "field_name" => self::toCamelCase($field)];
            } elseif ("float" == $mutating_type && "currency" != $subtype) {
                if (in_array($type, ['double', 'decimal'])) {
                    $res[] = ["field" => $field, "field_name" => self::toCamelCase($field)];
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

            if ("datetime" == $field_type) {
                $line["store"] = "\$this->store_datetime(\$validatedData, '$field');";
                $line["update"] = "\$this->store_datetime(\$validatedData, '$field');";
                $line["convert"] = "\$this->convert_datetime(\$$element, '$field');";
            } elseif ("picture" == $subtype || "file" == $subtype) {
                $line["store"] = "\$this->store_$subtype(\$validatedData, \"$field\", \$request, \"$element\");";
                $line["destroy"] = "if (\$$element->$field) \$this->destroy_file( \$$element->$field);";
                $line["update"] = "\$this->update_$subtype(\$validatedData, \"$field\", \$request, \"$element\", \$previous);";
            } elseif ("bitfield" == $subtype) {
                $line["store"] = "\$this->store_$subtype(\$validatedData, \"$field\", \$request, \"$element\");";
                $line["update"] = "\$this->update_$subtype(\$validatedData, \"$field\", \$request, \"$element\");";
            } elseif ("checkbox" == $subtype) {
                $line["store"] = "\$this->store_$subtype(\$validatedData, \"$field\", \$request, \"$element\");";
                $line["update"] = "\$this->store_$subtype(\$validatedData, \"$field\", \$request, \"$element\");";
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

            if ($subtype == "enumerate" || $subtype == "bitfield") {

                foreach ($options['values'] as $value) {
                    $line = [];
                    $line["name"] = $field . "." . $value;
                    $line["display_name"] = ucfirst(str_replace('_', ' ', $value));
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
                $foreign_table = Schema::foreignKeyReferencedTable($table, $field);
                $foreign_field = Schema::foreignKeyReferencedColumn($table, $field);
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
                $foreign_table = Schema::foreignKeyReferencedTable($table, $field);
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
     * 
     */
    static public function foreign_key_list($table) {
        $res = [];
        foreach (Schema::fieldList($table) as $field) {
            if (Schema::foreignKey($table, $field)) {
                $foreign_table = Schema::foreignKeyReferencedTable($table, $field);
                $foreign_class = Meta::class_name($foreign_table);
                $elt = [];
                $elt['foreign_table'] = $foreign_table;
                $elt['foreign_class'] = $foreign_class;
                $elt['foreign_element'] = Meta::element($foreign_table);
                $res[] = $elt;
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
            'rules_field_list' => self::form_field_list($table, true),
            'index_field_list' => self::index_field_list($table),
            'factory_field_list' => self::factory_field_list($table),
            'default_field_list' => self::default_field_list($table),
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
            'picture_url' => self::picture_file_url($table, "picture"),
            'download_url' => self::picture_file_url($table, "file"),
            'controller_list' => self::controller_list($table),
            'enumerate_list' => self::enumerate_list($table),
            'is_view' => $is_view,
            'foreign_keys' => self::foreign_keys($table),
            'foreign_key_list' => self::foreign_key_list($table),
            'factory_referenced_models' => self::factory_referenced_models($table),
            'filter_names' => Meta::filter_names($table),
            'filter_fields' => self::filter_field_list($table)
        );
    }
}
