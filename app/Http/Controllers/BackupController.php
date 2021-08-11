<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Helpers\TenantHelper;
use Illuminate\Support\Facades\Log;

class BackupController extends Controller
{
	
	/**
	 * Find the backup filename from the id
	 * @param unknown $id
	 * @return string
	 */
	private function filename_from_index($id, $fullpath = false) {
		
		$dirpath = TenantHelper::backup_dirpath();
		$backup_list = scandir($dirpath);

		// Look for the file specified by the user
		$filename = "";
		for ($i = 2; $i < count($backup_list); $i++) {
			$num_id = $i - 1;
			if (($num_id == $id) || ($backup_list[$i] == $id)) {
				if ($fullpath) {
					$filename = $dirpath . DIRECTORY_SEPARATOR . $backup_list[$i];
				} else {
					$filename = $backup_list[$i];
				}
				break;
			}
		}
		
		return $filename;
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    	
        $backups = array();
        $dirpath = storage_path() . "/app/backup/";
        $backup_list = scandir($dirpath);
        for ($i = 2; $i < count($backup_list); $i++) {
        	$elt = array('id' => ($i - 1), 'filename' => $backup_list[$i]);
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
    	$filename = $this->filename_from_index($id, true);
    	$short_filename = $this->filename_from_index($id);
    	
    	if ($filename) {
    		unlink($filename);
    		return redirect('/backup')->with('success', 'Backup ' . $short_filename . " deleted");
    	}
    	return redirect('/backup')->with('error', "Backup " . $id . " not found");    	
    }
}
