<?php
/**
 * This file is generated from a template with metadata extracted from the data model.
 * If modifications are required, it is important to consider if they should be done in the template
 * or in the generated file, in which case caution must be exerted to avoid overwritting.
 */

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ModelWithLogs;
use App\Helpers\Config;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;


/**
 * Motd model
 *
 * Acces to the percistency layer
 * motds is a regular table not a MySQL view
 *
 * @author fred
 *
 */
class Motd extends ModelWithLogs {

    use HasFactory;

    /**
     * The associated database table
     */
    protected $table = 'motds';
 
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
	protected $fillable = ["title", "message", "publication_date", "end_date"];
}