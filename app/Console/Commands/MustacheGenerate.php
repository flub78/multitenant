<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schema;
use App\Helpers\MustacheHelper;
use Illuminate\Support\Facades\Log;
use Exception;

class MustacheGenerate extends Command {
	protected $templates = [ "controller","model","request","index","create","edit","english","french"
	];

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'mustache:generate' 
			. ' {--compare : compare generated files with current version}' 
			. ' {--install : compare generated files with current version}' 
			. ' {--pretend : simulation, no actions}' 
			. ' {table : database table}' 
			. ' {template :  mustache template, all|controller|model|request|index|create|edit|english|french|test_model|test_controller}' 
			. '';

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
		$dirname = dirname($filename);
		if (!is_dir($dirname)) {
			mkdir($dirname, 0777, true);
		}

		// For some reason file_put_content generates a Failed to open stream: Permission denied
		$fh = fopen($filename, "w");
		fwrite($fh, $content);
		fclose($fh);
	}

	/**
	 * c
	 * Apply action on one template
	 *
	 * @param string $table
	 * @param string $template
	 */
	protected function process_file(string $table, string $template_file, string $result_file) {
		$verbose = $this->option('verbose');
		if ($verbose)
			echo "\nprocessing $table\n" . "template=$template_file\n" . "result=$result_file\n";

		// if ($verbose) echo "schema=" . env("DB_SCHEMA") . "\n";

		if ($verbose) {
			$mustache = new \Mustache_Engine([ 'logger' => Log::channel('stderr')
			]);
		} else {
			$mustache = new \Mustache_Engine();
		}
		$template = file_get_contents($template_file);

		$rendered = $mustache->render($template, MustacheHelper::metadata($table));
		if ($verbose)
			echo $rendered;
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
		$compare = $this->option('compare');
		$install = $this->option('install');
		$verbose = $this->option('verbose');
		$pretend = $this->option('pretend');
		
		if (!Schema::tableExists($table)) {
			$this->error("Unknow table $table in tenant database");
			return 1;
		}

		try {
			if ($template == "all") {
				foreach ($this->templates as $template) {
					$template_file = MustacheHelper::template_file($table, $template);
					$result_file = MustacheHelper::result_file($table, $template);
					$this->process_file($table, $template_file, $result_file);
				}
			} else {
				$template_file = MustacheHelper::template_file($table, $template);
				$result_file = MustacheHelper::result_file($table, $template);
				$this->process_file($table, $template_file, $result_file);
			}
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
			return 1;
		}
		
		if ($compare) {
			$comparator = "WinMergeU";
			if ($template == "all") {
				foreach ($this->templates as $template) {
					$result_file = MustacheHelper::result_file($table, $template);
					$install_file = MustacheHelper::result_file($table, $template, $install=true);
					$cmd = "$comparator $result_file $install_file";
					if ($verbose) echo "\ncmd = $cmd";
					
					$returnVar = NULL;
					$output = NULL;										
					if (!$pretend) exec ( $cmd, $output, $returnVar );
				}
			} else {
				$result_file = MustacheHelper::result_file($table, $template);
				$install_file = MustacheHelper::result_file($table, $template, $install=true);
				$cmd = "$comparator $result_file $install_file";
				if ($verbose) echo "\ncmd = $cmd";
				
				$returnVar = NULL;
				$output = NULL;
				if (!$pretend) exec ( $cmd, $output, $returnVar );
			}
		}

		if ($install) {
			echo "\ninstalling ...\n";
		}
		
		return 0;
	}

}
