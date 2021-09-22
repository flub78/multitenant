<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schema;
use App\Helpers\MustacheHelper;
use Illuminate\Support\Facades\Log;

class MustacheGenerate extends Command {
	
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'mustache:generate' 
			. ' {table : database table}' 
			. ' {template :  mustache template}' 
			. ' {result}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate code with a mustache template';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	
	protected function write_file(string $content, string $filename) {
		
		$dirname =  dirname($filename);
		if (!is_dir($dirname)) {
			mkdir($dirname, 0777, true);
		}
		
		// For some reason file_put_content generates a Failed to open stream: Permission denied
		$fh = fopen($filename, "w");
		fwrite($fh, $content);
		fclose($fh);	
	}
	
	/**c
	 * Apply action on one template
	 *
	 * @param string $table
	 * @param string $template
	 */
	protected function process_file(string $table, string $template_file, string $result_file) {
		$verbose =  $this->option('verbose');
		if ($verbose) echo  "\nprocessing $table\n" 
			. "template=$template_file\n" 
			. "result=$result_file\n";
		
		// if ($verbose) echo "schema=" . env("DB_SCHEMA") . "\n"; 
	
		if ($verbose) {
			$mustache = new \Mustache_Engine(['logger' => Log::channel('stderr')]);
		} else {
			$mustache = new \Mustache_Engine();
		}
		$template = file_get_contents($template_file);
		
		$rendered = $mustache->render($template, MustacheHelper::metadata($table));
		if ($verbose) echo $rendered;
		$this->write_file($rendered, $result_file);
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle() {
		$table = $this->argument('table');
		$template = $this->argument('template');
		$result = $this->argument('result');

		if (!Schema::tableExists($table)) {
			$this->error("Unknow table $table in tenant database");
			return 1;
		}

		$dir = MustacheHelper::template_dirname($template);

		if ($dir) {
			// todo : recursive processing >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			echo "$dir is a dir";
			return;
		} else {
			$template_file = MustacheHelper::template_filename($template);
			if (!$template_file) {
				$this->error("Template $template not found");
				return 1;
			}
			$result_file = MustacheHelper::result_filename($result);			
			$this->process_file($table, $template_file, $result_file);
		}


		return 0;
	}

}
