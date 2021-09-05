<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Schema;
use Exception;

class Metadata extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['table', 'field', 'subtype', 'options', 'foreign_key', 'target_table', 'target_field'];

    
    /**
     * 
     * Overload create to check that the references to table and field exist
     * @param array $params
     * @throws Exception
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
     */
    public static function create(array $params = []) {
    	
		$info = Schema::columnInformation($params["table"], $params["field"]);
		
		if (!$info)
			throw new Exception("field " . $params["field"] . " from table " . $params["table"] . " not found");
							
    	// do something here
    	return static::query()->create($params);
    }    
    
    /**
     * Update the model in the database.
     *
     * @param  array  $attributes
     * @param  array  $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = []) {
    	/*
    	 * For some unknown reason direct access to $this->table does not return
    	 * the correct value in this context
    	 */
    	$table = $this->attributes['table'];
    	$field = $this->attributes['field'];
    	$info = Schema::columnInformation($table, $field);
     	if (!$info)
    		throw new Exception("field $field from table $table  not found");
     	
    	return parent::update($attributes, $options);
    }
    
    
}
