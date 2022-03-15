<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Response;
use Illuminate\Support\Facades\Storage;
use App\Helpers\BladeHelper as Blade;



class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    protected function upload_name($name, $context) {
    	return Blade::upload_name($name, $context);
    }
    
    /**
     * Download a file from the uploads storage
     * @param String $file
     */
    public function download($file) {
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
    
}
