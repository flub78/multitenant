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
 * PersonalAccessToken model
 *
 * Acces to the percistency layer
 * personal_access_tokens is a regular table not a MySQL view
 *
 * @author fred
 *
 */
class PersonalAccessToken extends ModelWithLogs {

    use HasFactory;

    /**
     * The associated database table
     */
    protected $table = 'personal_access_tokens';

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
    protected $fillable = ["tokenable_type", "tokenable_id", "name", "token", "abilities", "last_used_at"];

    public function tokenable() {
        return $this->morphTo();
    }
}
