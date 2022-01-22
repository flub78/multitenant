<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Schema;
use Exception;

/**
 * Metadata Model
 * 
 * Metada complements the data which can be found inside the database itself on 
 * the database structure . These metadata gives additional information about table or view field
 * by specifying for example that a VARCHAR is used to store an email address.
 * 
 * @author frederic
 *
 */
class Metadata extends Model
{
    use HasFactory;
    
    protected $connection = 'schema';

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
    	 * the correct value in this context (very likely because table already exists in eloquent models)
    	 */
    	$table = $this->attributes['table']; // instead of $this->table
    	$field = $this->attributes['field'];
    	$info = Schema::columnInformation($table, $field);
     	if (!$info)
    		throw new Exception("field $field from table $table  not found");
     	
    	return parent::update($attributes, $options);
    }
    
    /**
     * full_name attribute
     * As names are unique, just create aliases
     * @return string
     */
    public function getFullNameAttribute() {
    	return "metadata " . $this->attributes['table'] . '.' . $this->field;
    }
    
    
    /**
     * short_name attribute
     * @return string
     */
    public function getShortNameAttribute() {
    	return $this->full_name;
    }
    
    public static function subtype($table, $field) {
    	$meta = self::where(['table' => $table, "field" => $field])->first();
    	return ($meta) ? $meta->subtype : '';
    }
    
    public static function options($table, $field) {
    	$meta = self::where(['table' => $table, "field" => $field])->first();
    	return ($meta) ? json_decode($meta->options, true) : [];
    }
    
    
 }
