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
 * Attachment model
 *
 * Acces to the percistency layer
 * attachments is a regular table not a MySQL view
 *
 * @author fred
 *
 */
class Attachment extends ModelWithLogs {

    use HasFactory;

    /**
     * The associated database table
     */
    protected $table = 'attachments';

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
    protected $fillable = ["referenced_table", "referenced_id", "user_id", "filename", "description", "file"];

    /**
     * Override the delete method to remove the physical file before deleting the record
     *
     * @return bool|null
     */
    public function delete() {
        $filename = 'uploads/' . $this->file;
        if ($this->file && \Illuminate\Support\Facades\Storage::exists($filename)) {
            \Illuminate\Support\Facades\Storage::delete($filename);
        }

        return parent::delete();
    }
}
