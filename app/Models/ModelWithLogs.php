<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * A layer between the application models and the eloquent abstract model class to
 * log actions modifying the database.
 * 
 * As there is a big number of public methods, this class will be completed along
 * the way of the development.
 * 
 * The goal of this class is to get enough information to track the database changes for troubleshooting.
 * 
 * Reference: /vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php
 * 
 * @author frederic
 *
 */
class ModelWithLogs extends Model
{
     
    /**
     * Log a mesage and add information on the object, the action and the user
     * who performs the action.
     * 
     * @param string $msg
     *
     * @SuppressWarnings("PMD.ShortVariable")
     */
    protected function logit(string $msg = '') {
        
        $attrs = get_object_vars ($this);
        $str = json_encode($attrs['attributes']);
        
        $bt = debug_backtrace(0 , 2);
        $str .= '->' . $bt[1]['function'] . ' ';
        
        $user = Auth::user();
        if ($user) {
            $str .= ' by ' . $user->name;
        } else {
            $str .= ' by guest';
        }
        
        Log::Debug($msg . $str);
    }
    
    /**
     * Update the model in the database.
     *
     * @param  array  $attributes
     * @param  array  $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        $str = "attributes=" . implode(', ', $attributes);
        $this->logit($str);
        return parent::update($attributes, $options);
    }
    
    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
    {
        if ($options) {
            $str = "options=" . implode(', ', $options);
        } else {
            $str = '';
        }
        $this->logit($str);     
        return parent::save($options);
    }
    
    /**
     * Delete the model from the database.
     *
     * @return bool|null
     *
     * @throws \LogicException
     */
    public function delete()
    {
        $this->logit();
        return parent::delete();
    }
}
