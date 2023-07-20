<?php

namespace App\Helpers;

use App\Helpers\MetadataHelper as Meta;
use App\Helpers\HtmlHelper as HH;
use App\Helpers\Cg;

/**
 * Code generation
 */
class CgDate extends Cg {

    /**
     * Create a new code generation instance
     * 
     */
    public function __construct(string $type, string $subtype) {
        parent::__construct($type, $subtype);
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

        if ($view) {
            $element = Meta::element($view);
            $value = '$' . $element . '->' . $view_field;
        } else {
            $element = Meta::element($table);
            $value = '$' . $element . '->' . $field;
        }

        return '{{DateFormat::to_local_date(' . $value . ')}}';
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

        $class = 'form-control';

        // $class .= ' datepicker';  no more used with HTML 5
        $type = 'date';

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

        $class = 'form-control';
        $prefix = "";

        $type = 'date';

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

        $options = Meta::field_metadata($table, $field);

        $rules = [];
        $rules[] = "''";

        if ($options && array_key_exists("min", $options)) {
            $size = $options['min'];
            $rules[] = "'min:$size'";
        }

        if ($options && array_key_exists("max", $options)) {
            $size = $options['max'];
            $rules[] = "'max:$size'";
        }

        if (Meta::unique($table, $field)) {
            $rules[] = "'unique:$table'";
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

        $options = Meta::field_metadata($table, $field);

        $rules = [];

        $rules[] = "''";

        if ($options && array_key_exists("min", $options)) {
            $size = $options['min'];
            $rules[] = "'min:$size'";
        }

        if ($options && array_key_exists("max", $options)) {
            $size = $options['max'];
            $rules[] = "'max:$size'";
        }

        if (Meta::unique($table, $field)) {
            $rules[] = "'unique:$table'";
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

        // Tests relies on random elements to be different
        $faker = '$this->faker';

        $res = "$table.$field faker type=$type, subtype=$subtype\n";

        $res = $faker . '->date(__("general.database_date_format"))';

        return $res;
    }

    /**
     * Generation of the database migration
     *
     * @param String $table
     * @param String $field
     * @return string
     */
    public function field_migration(String $table, String $field) {

        $comment = Meta::columnComment($table, $field);

        $res = '$table';

        $res .= "->date('$field')";

        if (!Meta::required($table, $field)) {
            $res .= '->nullable()';
        }

        if ($comment) {
            $res .= "->comment('$comment')";
        }

        $res .= ';';
        return $res;
    }

}
