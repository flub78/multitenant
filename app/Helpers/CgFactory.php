<?php
namespace App\Helpers;

use App\Helpers\Cg;
use App\Helpers\CgDate;

/**
 * Abstract class for code generation
 */
class CgFactory {

    /**
     * Create a new code generation instance
     *
     * @param string $type
     * @param string $subtype
     * @return Cg
     */
    public static function instance (string $type = "", string $subtype = "") {

        if ($type == "date") {
            return new CgDate($type, $subtype);
        }
        
        return new Cg($type, $subtype);
    }

}