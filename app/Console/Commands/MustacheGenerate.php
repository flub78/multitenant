<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schema;
use App\Helpers\MustacheHelper;
use Illuminate\Filesystem\Filesystem;


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

	/**c
	 * Apply action on one template
	 *
	 * @param string $table
	 * @param string $template
	 */
	protected function process_file(string $table, string $template_file, string $result_file) {
		echo "\nprocessing $table $template_file $result_file";
	
		$mustache = new \Mustache_Engine;
		$template = file_get_contents($template_file);
		
		$rendered= $mustache->render($template, array('planet' => 'World'));
		$result_file = 'storage\app\code\mustache.res';
		
		echo "\nrendered = $rendered";
		echo "\nwriting to $result_file";
		if (file_exists($result_file)) unlink($result_file, );
		
		// TODO fix the Failed to open stream: No such file or directory
		// file_put_contents($rendered, $result_file);
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
