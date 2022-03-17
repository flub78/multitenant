<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Response;
use Illuminate\Support\Facades\Storage;
use App\Helpers\BladeHelper as Blade;
use App\Helpers\DateFormat;



class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /**
     * Generate the name where an uploaded file is stored
     * @param unknown $name
     * @param unknown $table_field defines the context
     * @return string
     */
    protected function upload_name($name, $table_field) {
    	return Blade::upload_name($name, $table_field);
    }
    
    /**
     * Download a file from the uploads storage
     * @param String $file
     */
    public function download_file($file) {
    	if (!$file) return redirect ( 'code_gen_type' )->with ( 'error', "File not found");
    	
    	return Storage::download("uploads/" . $file);
    }
    
    /**
     * Display an image in a browser
     *
     * @param unknown $filename
     * @return unknown
     */
    public function displayImage($filename) {
    	$content = Storage::get('uploads/' . $filename);
    	$mime = Storage::mimeType('uploads/' . $filename);
    	$response = Response::make($content, 200);
    	$response->header("Content-Type", $mime);
    	return $response;
    }
    
    /*
     * Store functions
     */
    
    /**
     * @param unknown $validatedData
     * @param unknown $field
     */
    public function store_date(&$validatedData, $field) {
    	$validatedData [$field] = DateFormat::datetime_to_db ( $validatedData [$field]);
    }
    
    /**
     * @param unknown $validatedData
     * @param unknown $field
     */
    public function store_datetime_with_date_and_time(&$validatedData, $field) {
    	$validatedData[$field] = $validatedData[$field . '_date'] . ' ' . $validatedData[$field . '_time'] . ':00';
    }
    
    /**
     * @param unknown $validatedData
     * @param unknown $field
     * @param unknown $request
     * @param unknown $table
     */
    public function store_picture(&$validatedData, $field, $request, $table) {
    	$this->store_file($validatedData, $field, $request, $table);
    }

    /**
     * @param unknown $validatedData
     * @param unknown $field
     * @param unknown $request
     * @param unknown $table
     */
    public function store_file(&$validatedData, $field, $request, $table) {
    	if ($request->file($field)) {
    		$name =  $request->file($field)->getClientOriginalName();
    		$filename = $this->upload_name($name, $table . '_' . $field);
    		$request->file($field)->storeAs('uploads', $filename);
    		$validatedData[$field] = $filename;
    	}
    	if (array_key_exists($field, $validatedData) && !$validatedData[$field]) unset($validatedData[$field]);
    }

    /*
     * Update functions
     */
    
    /**
     * @param unknown $validatedData
     * @param unknown $field
     */
    public function update_date(&$validatedData, $field) {
    	$validatedData [$field] = DateFormat::datetime_to_db ( $validatedData [$field]);
    }
    
    /**
     * @param unknown $validatedData
     * @param unknown $field
     */
    public function update_datetime_with_date_and_time(&$validatedData, $field) {
    	$validatedData [$field] = DateFormat::datetime_to_db ( $validatedData [$field . '_date'], $validatedData [$field . '_time'] );
    	unset($validatedData[$field . '_date']);
    	unset($validatedData[$field . '_time']);
    }
    
    /**
     * @param unknown $validatedData
     * @param unknown $field
     * @param unknown $request
     * @param unknown $table
     * @param unknown $previous
     */
    public function update_picture(&$validatedData, $field, $request, $table, $previous) {
    	$this->update_file($validatedData, $field, $request, $table, $previous);
    }
    
    /**
     * @param unknown $validatedData
     * @param unknown $field
     * @param unknown $request
     * @param unknown $table
     * @param unknown $previous
     */
    public function update_file(&$validatedData, $field, $request, $table, $previous) {
    	if ($request->file($field)) {
    		$name =  $request->file($field)->getClientOriginalName();
    		$filename = $this->upload_name($name, $table . '_' . $field);
    		if ($previous->$field) {
    			Storage::delete('uploads/' . $previous->$field);
    		}
    		$request->file($field)->storeAs('uploads', $filename);
    		$validatedData[$field] = $filename;
    	}
    	if (array_key_exists($field, $validatedData) && !$validatedData[$field]) unset($validatedData[$field]);
    }

    /*
     * Destroy functions
     */
    
    /**
     * @param unknown $file
     * @param unknown $context
     */
    public function destroy_file($file) {
    	if ($file) {
    		Storage::delete('uploads/' . $file);
    	}   	
    }
}
