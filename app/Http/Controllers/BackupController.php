<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\TenantHelper;
use App\Helpers\BackupHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Backup controller
 * GUI and logic to create, list, delete, upload and download backups
 * 
 * @author frederic
 * @reviewed 2021-29-08
 */
class BackupController extends Controller
{
		
	/**
	 * Find a backup, return a storage related filename
	 * @param integer $id
	 * @return mixed|NULL
	 * @SuppressWarnings("PMD.ShortVariable")
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
    	if (!$tenant) $tenant = "";
    	
    	$database = TenantHelper::tenant_database ( $tenant );
    	$fullname = TenantHelper::backup_fullname ( $tenant );
    	$filename = basename($fullname);
    	
    	$res = BackupHelper::backup($database, $fullname, env('DB_HOST'), env('DB_USERNAME'),  env ('DB_PASSWORD'));
    	if ($res)
    		return $this->index()->with('success', __('backup.created', ['id' => $filename]) );
    	else 
    		return $this->index()->with('error', __('backup.error', ['id' => $filename]) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @SuppressWarnings("PMD.ShortVariable")
     */
    public function restore($id)
    {
    	$fullname = $this->filename_from_index($id, true);
    	$filename = ($fullname) ? basename($fullname) : '';
        
    	$tenant = (tenant('id') ? tenant('id') : "");
    	Log::Debug("BackupController.restore($id), filename=$filename, tenant=$tenant");
    	Log::Debug("DB_USERNAME = " .  env('DB_USERNAME'));
    	
        if ($fullname) {        	
        	$database = TenantHelper::tenant_database ( $tenant );
        	
        	BackupHelper::restore($fullname, $database);
        	return redirect('/backup')->with('success', __('backup.restored', ['id' => $filename]) );
        }

        Log::Error("BackupController.restore($id), filename=$filename, tenant=$tenant, backup not found");
        
        return redirect('/backup')->with('error', __('backup.not_found', ['id' => $id]) );
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @SuppressWarnings("PMD.ShortVariable")
     */
    public function destroy($id)
    {
    	$filename = $this->filename_from_index($id);
    	
    	if ($filename) {
    		Storage::delete($filename);
    		return redirect('/backup')->with('success', __('backup.deleted', ['id' => $id]));
    	}
    	return redirect('/backup')->with('error', __('backup.not_found', ['id' => $id]));
    }
    
    
    /**
     * Download a backup
     * @param unknown $id
     * @SuppressWarnings("PMD.ShortVariable")
     */
    public function download($id) {
    	
    	$filename = $this->filename_from_index($id);    	
    	if ($filename) 
    		return Storage::download($filename);
    	else
    		return redirect('/backup')->with('error', __('backup.not_found', ['id' => $id]));
    	
    }
    
    /**
     * Let the user upload a backup into storage
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function upload(Request $request) {
    	if ($request->file('backup_file')) {
    		$name =  $request->file('backup_file')->getClientOriginalName();
    		$request->file('backup_file')->storeAs('backup', $name);
    		return redirect('/backup')->with('success', "Backup $name uploaded");
    	} else {
    		return redirect('/backup');
    	}
    }
}
