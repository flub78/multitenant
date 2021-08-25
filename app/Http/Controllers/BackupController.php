<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Helpers\TenantHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


/**
 * Backup controller
 * GUI and logic to create, list, delete, upload and download backups
 * 
 * TODO Refactoring using Laravel storage to avoid references to the file system.
 * 
 * @author frederic
 *
 */
class BackupController extends Controller
{
		
	/**
	 * Find a backup, return a storage related filename
	 * @param unknown $id
	 * @return mixed|NULL
	 */
	private function filename_from_index ($id, $fullpath = false) {
		$backup_list = Storage::files('backup');

		if (($id >=1) && ($id <= count($backup_list))) {
			if ($fullpath) {
				$filename = TenantHelper::storage_dirpath(tenant('id') ? tenant('id') : "")
				. DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR .$backup_list[$id - 1];
				return $filename;
			} else {
				return $backup_list[$id - 1];				
			}
		} else {
			return "";
		}
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    	
        $backups = array();
        $backup_list = Storage::files('backup');
        for ($i = 0; $i < count($backup_list); $i++) {
        	$elt = array('id' => ($i + 1), 'filename' => basename($backup_list[$i]));
        	array_push($backups, $elt);
        }
        return view("backup.index", compact('backups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	$tenant = tenant('id');
    	if ($tenant)
    		Artisan::call("backup:create --tenant=$tenant");
    	else 
        	Artisan::call('backup:create', []);
        return $this->index();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
    	$filename = $this->filename_from_index($id);
    	if ($filename) $filename = basename($filename);
        
    	$tenant = tenant('id');
    	Log::Debug("BackupController.restore($id), filename=$filename, tenant=$tenant");
    	Log::Debug("DB_USERNAME = " .  env('DB_USERNAME'));
        if ($filename) {
        	
        	if ($tenant) {
        		Artisan::call('backup:restore', ['backup_id' => $filename, '--force' => true, '--tenant' => $tenant]);        		
        	} else {
        		Artisan::call('backup:restore', ['backup_id' => $filename, '--force' => true]);
        	}
        	return redirect('/backup')->with('success', 'Backup ' . $filename . " restored");
        }

        Log::Error("BackupController.restore($id), filename=$filename, tenant=$tenant, backup not found");
        
        return redirect('/backup')->with('error', "backup " . $id . " not found");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$filename = $this->filename_from_index($id);
    	
    	if ($filename) {
    		Storage::delete($filename);
    		// TODO localize success message
    		return redirect('/backup')->with('success', 'Backup ' . basename($filename) . " deleted");
    	}
    	return redirect('/backup')->with('error', "Backup " . $id . " not found");    	
    }
    
    
    /**
     * Download a backup
     * @param unknown $id
     */
    public function download($id) {
    	
    	$filename = $this->filename_from_index($id);    	
    	if ($filename) 
    		return Storage::download($filename);
    	else
    		return redirect('/backup')->with('error', "Backup " . $id . " not found");
    	
    }
}
