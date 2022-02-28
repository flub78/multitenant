<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerced to avoid overwritting.
 */

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ModelWithLogs;


/**
 * CodeGenType model
 *
 * Acces to the percistency layer
 *
 * @author fred
 *
 */
class CodeGenType extends ModelWithLogs {

    use HasFactory;

    /**
     * The associated database table
     */
    protected $table = 'code_gen_types';
    
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ["name", "phone", "description", "year_of_birth", "weight", "birthday", "tea_time", "price", "big_price", "qualifications", "picture", "attachment"];
}