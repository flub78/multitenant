<?php

namespace App\Helpers;

use App\Helpers\MetadataHelper as Meta;
use App\Helpers\HtmlHelper as HH;
use App\Helpers\BladeHelper as Blade;

/**
 * Parent class for code generation
 */
class Cg {

    // $type and $subtype are temporary parameters for the constructor. They will dispappear once there is one class per type and subtype
    protected $type;
    protected $subtype;

    /**
     * Create a new code generation instance
     */
    public function __construct(string $type, string $subtype) {
        $this->type = $type;
        $this->subtype = $subtype;
    }

    /**
     * return a string at the format '["elt1", "elt2"]' from an array of string
     * 
     * @param array $array
     */
    static public function array_to_string(array $array) {
        $res = '[';
        if ($array) {
            $res .= '"' . implode('", "', $array) . '"';
        }
        $res .= ']';
        return $res;
    }

    // ===============================================================================================
    /**
     * Generate code to display an element in a table list view
     *
     * @param String $table
     * @param String $field
     * @return string
     */
    public function field_display(String $table, String $field, String $view = "", String $view_field = "") {

        $field_type = $this->type;
        $subtype = $this->subtype;

        if ($view) {
            $element = Meta::element($view);
            $value = '$' . $element . '->' . $view_field;
        } else {
            $element = Meta::element($table);
            $value = '$' . $element . '->' . $field;
        }

        if ($field_type == "datetime") {
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
    public function field_label(String $table, String $field, String $view = "", String $view_field = "") {
        $subtype = $this->subtype;

        $element = Meta::element($table);
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

        $fkt = Meta::foreignKeyReferencedTable($table, $field);
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
     * Generate code for input in an edit form
     *
     * @param String $table
     * @param String $field
     * @return string
     */
    public function field_input_edit(String $table, String $field): string {
        $type = "text";
        $element = Meta::element($table);
        $field_type = $this->type;
        $subtype = $this->subtype;

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

        $fkt = Meta::foreignKeyReferencedTable($table, $field);
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
            if (!$options) return "";
            if (!array_key_exists("values", $options)) return "";
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
    public function field_label_input_edit(String $table, String $field) {
        $type = "text";
        $element = Meta::element($table);
        $subtype = $this->subtype;

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
            $res .= '                  ' . $this->field_input_edit($table, $field) . "\n";
            $res .= '                  ' . $this->field_label($table, $field) . "\n";
            $res .= '              </div>' . "\n";

            $res .= '              <div class="m-2">' . "\n";
            $res .= '                  ' . $prefix . "\n";
            $res .= '              </div>' . "\n";

            $res .= '          </div>' . "\n";
            return $res;
        }

        if ($subtype == "checkbox") {
            $res = '<div class="form-group mb-2 border">' . "\n";
            $res .= '              ' . $this->field_label($table, $field) . "\n";
            $res .= '              ' . $this->field_input_edit($table, $field) . "\n";
            $res .= '          </div>' . "\n";
            return $res;
        }

        $res = '<div class="form-floating mb-2 border">' . "\n";
        $res .= '              ' . $this->field_input_edit($table, $field) . "\n";
        $res .= '              ' . $this->field_label($table, $field) . "\n";
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
    public function field_label_input_create(String $table, String $field) {
        $type = "text";
        $element = Meta::element($table);
        $field_type = $this->type;
        $subtype = $this->subtype;


        if ($subtype == "checkbox") {

            $res = '<div class="form-group mb-2 border">' . "\n";
            $res .= '              ' . $this->field_label($table, $field) . "\n";
            $res .= '              ' . $this->field_input_create($table, $field) . "\n";
            $res .= '          </div>' . "\n";
            return $res;
        }

        $res = '<div class="form-floating mb-2 border">' . "\n";
        $res .= '              ' . $this->field_input_create($table, $field) . "\n";
        $res .= '              ' . $this->field_label($table, $field) . "\n";
        $res .= '          </div>' . "\n";
        return $res;
    }


    /**
     * Generate a validation rule for an edit input field
     *
     * @param String $table
     * @param String $field
     * @return string
     
     * @SuppressWarnings("PMD.ShortVariable")
     */
    public function field_rule_edit(String $table, String $field) {
        $field_type = $this->type;
        $subtype = $this->subtype;

        $element = Meta::element($table);
        $primary_index = Meta::primaryIndex($table);
        $options = Meta::field_metadata($table, $field);

        $rules = [];
        if ($subtype != "checkbox") {
            if (Meta::required($table, $field)) {
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
        } elseif ($size = Meta::columnSize($table, $field)) {
            if (($subtype == "picture") || ($subtype == "file")) {
                $size = 2000;
            }
            if (in_array($field_type, ['varchar', 'text'])) {
                $rules[] = "'max:$size'";
            }
        }

        if ($subtype == 'picture') {
            $rules[] = "'mimes:jpeg,bmp,png,webp,gif,svg'";
        }

        if ($subtype == 'email') {
            $rules[] = "'email'";
        }

        $fkt = Meta::foreignKeyReferencedTable($table, $field);
        if ($fkt) {
            // the field is a foreign key
            $fkc = Meta::foreignKeyReferencedColumn($table, $field);
            $rules[] = "'exists:$fkt,$fkc'";
        } elseif (Meta::unique($table, $field)) {
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
    public function field_rule_create(String $table, String $field) {
        $field_type = $this->type;
        $subtype = $this->subtype;

        $options = Meta::field_metadata($table, $field);

        $rules = [];
        if ($subtype != "checkbox") {
            if (Meta::required($table, $field)) {
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
        } elseif ($size = Meta::columnSize($table, $field)) {
            if (($subtype == "picture") || ($subtype == "file")) {
                $size = 2000;
            }
            if (in_array($field_type, ['varchar', 'text'])) {
                $rules[] = "'max:$size'";
            }
        }

        if ($subtype == 'picture') {
            $rules[] = "'mimes:jpeg,bmp,png,webp,gif,svg'";
        }

        if ($subtype == 'email') {
            $rules[] = "'email'";
        }

        $fkt = Meta::foreignKeyReferencedTable($table, $field);
        if ($fkt) {
            // the field is a foreign key
            $fkc = Meta::foreignKeyReferencedColumn($table, $field);
            $rules[] = "'exists:$fkt,$fkc'";
        } elseif (Meta::unique($table, $field)) {
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


    /**
     * Faker are used to create random elements for testing
     *
     * @param String $table
     * @param String $field
     * @return string
     */
    public function field_faker(String $table, String $field) {

        $type = $this->type;
        $subtype = $this->subtype;

        // $unique = Meta::unique($table, $field);
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
            $target_table = Meta::foreignKeyReferencedTable($table, $field);
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
     * TODO Check that all types of integers are supported
     *
     */
    public function field_migration(String $table, String $field) {

        $type = $this->type;
        $subtype = $this->subtype;
        $unique = Meta::unique($table, $field);
        $options = Meta::field_metadata($table, $field);
        $comment = Meta::columnComment($table, $field);
        $unsigned = Meta::unsignedType($table, $field);

        $res = '$table';
        $json = [];

        if ('id' == $field) {
            $res .= "->id();";
            return $res;
        }

        if ('varchar' == $type) {
            $size = Meta::columnSize($table, $field);
            $res .= "->string('$field', $size)";
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

        if (!Meta::required($table, $field)) {
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
    public function field_default(String $table, String $field) {

        $type = $this->type;
        $subtype = $this->subtype;
        $res = '';
        $info = Meta::columnInformation($table, $field);
        if (!$info || !$info->Default) return "";

        $res = '$data["' . $field . '"] = "' . $info->Default . '";';

        return $res;
    }
}
