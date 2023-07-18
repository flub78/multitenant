<?php

namespace App\Helpers;

use App\Helpers\MetadataHelper as Meta;
use App\Helpers\HtmlHelper as HH;
use App\Helpers\BladeHelper as Blade;
use App\Helpers\CgFactory;

/**
 * Code Generator
 *
 * This helper generate code from the metadata information.
 *
 * @author frederic
 *
 * TODO: code generator refactoring
 * 
 * Schema: model to get information from the database
 * Meatada: a class for the code generator to get information on metadata
 * The code generator should never use schema directly
 * 
 * And split the code generator with objects handling each subtypes.
 */
class CodeGenerator {

    // ###############################################################################################################
    // Code Generation
    // ###############################################################################################################


    /**
     * Transform an underscore separated string into camel case
     *
     * @param string $string
     * @param boolean $capitalizeFirstCharacter
     * @return mixed
     */
    static public function toCamelCase(string $string, $capitalizeFirstCharacter = true) {

        $str = str_replace('_', ' ', $string);
        $str = ucwords($str);
        $str = str_replace(' ', '', $str);

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }

    /**
     * Generate a dusk anchor
     * @param String $table
     * @param String $element
     * @param String $type
     * @return string
     */
    static public function dusk(String $table, String $element, String $type = "edit") {

        $dusk_field = ($table == "users") ? "name" : Meta::primaryIndex($table);
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
     * helper function to know if an array is associative
     *
     * @param unknown $array
     * @return boolean
     */
    static function isAssoc($array) {
        return ($array !== array_values($array));
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
        $primary_index = Meta::primaryIndex($table);


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
        $primary_index = Meta::primaryIndex($table);
        $element = Meta::element($table);
        $id = $element . '->' . $primary_index;
        $dusk = self::dusk($table, $element, "edit");
        $route = "{{ route('$element.edit', \$$id) }}";
        // $label = "{{ __('general.edit') }}";
        $label = '<i class="fa-solid fa-pen-to-square"></i>';
        return '<a href="' . $route . '" class="btn btn-primary" dusk="' . $dusk . '">' . $label . '</a>';
    }

    /**
     * Metadata all metadata for an individual field
     *
     * @param String $table
     * @param String $field
     * @return Array[]
     */
    static public function field_metadata(String $table, String $field, String $view = "", String $view_field = "") {

        $type = Meta::type($table, $field);
        $subtype = Meta::subtype($table, $field);

        $cg = CgFactory::instance($type, $subtype);

        if ('bitfield_boxes' == $subtype) $field = substr($field, 0, -6);  // remove '_boxes'
        $element = Meta::element($table);

        $res = [
            'name' => $field,
            'display'   => $cg->field_display($table, $field, $view, $view_field),   
            'label' => $cg->field_label($table, $field, $view, $view_field),
            'label_input_edit' => $cg->field_label_input_edit($table, $field),
            'label_input_create' => $cg->field_label_input_create($table, $field),
            'rule_edit' => $cg->field_rule_edit($table, $field),
            'rule_create' => $cg->field_rule_create($table, $field),
            'faker' => $cg->field_faker($table, $field),
            'display_name' => ucfirst(str_replace('_', ' ', $field)),
            'element_name' => $element . '.' . $field,
            'migration' => $cg->field_migration($table, $field),
            'default' => $cg->field_default($table, $field),
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
        $list = Meta::fieldList($table);
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
        $view_def = Meta::isView($table);        // is it a MySQL view ?

        if ($view_def) {
            $view_list = Meta::ScanViewDefinition($view_def);
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
        $view_def = Meta::isView($table);

        if ($view_def) {
            // Does it really make sense to generate a factory for a view ?
            $view_list = Meta::ScanViewDefinition($view_def);
            foreach ($view_list as $view_field) {
                $res[] = self::field_metadata($view_field['table'], $view_field['field'], $table, $view_field['name']);
            }
        } else {
            $list = Meta::fieldList($table);
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

        $list = Meta::fieldList($table);
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

                $target_table = Meta::foreignKeyReferencedTable($table, $field);
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
        $primary = Meta::primaryIndex($table);
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
        $list = Meta::fieldList($table);

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
        foreach (Meta::fieldList($table) as $field) {
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
        foreach (Meta::fieldList($table) as $field) {
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
        foreach (Meta::fieldList($table) as $field) {
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

        foreach (Meta::fieldList($table) as $field) {
            if (Meta::foreignKey($table, $field)) {
                $foreign_table = Meta::foreignKeyReferencedTable($table, $field);
                $foreign_field = Meta::foreignKeyReferencedColumn($table, $field);
                $res .= "\n\t\t\t";
                $res .= "\$table->foreign('$field')->references('$foreign_field')->on('$foreign_table')";

                // Fetch the type: restrict | cascade | no action from the database ...
                $delete_rule = Meta::onDeleteConstraint($table, $field);
                $update_rule = Meta::onUpdateConstraint($table, $field);

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
        foreach (Meta::fieldList($table) as $field) {
            if (Meta::foreignKey($table, $field)) {
                $foreign_table = Meta::foreignKeyReferencedTable($table, $field);
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
        foreach (Meta::fieldList($table) as $field) {
            if (Meta::foreignKey($table, $field)) {
                $foreign_table = Meta::foreignKeyReferencedTable($table, $field);
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
        $is_view = Meta::isView($table);
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
            'primary_index' => Meta::primaryIndex($table),
            'select_list' => self::select_list($table),
            'id_data_type' => self::id_data_type($table),
            'is_referenced' => (!$is_view && Meta::isReferenced($table)) ? "true" : "",
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
