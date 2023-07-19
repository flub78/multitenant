<?php
namespace App\Helpers;

use App\Helpers\Cg;

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
        return new Cg($type, $subtype);
    }

}