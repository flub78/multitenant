<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schema;
use App\Helpers\MustacheHelper;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * php artisan mustache:generate
 *
 * Generate, compare or install code file generated from a mustache template and fed by database
 * schema and metadata.
 *
 * @author frederic
 *
 */
class MustacheGenerate extends Command {
	protected $templates = [ "controller","model","request","index","create","edit","english","french"
	];

	/*
	 * "test_model","test_controller","test_dusk"
	 */
	
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
			. ' {template :  mustache template, all|controller|model|request|index|create|edit|english|french|test_model|test_controller|test_dusk}' 
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
		$install = $this->option('install');
		if ($verbose) {
			echo "\nprocessing $table\n" . "template=$template_file\n" . "result=$result_file\n";
			echo "Metadata schema=" . env("DB_SCHEMA") . "\n";
		}

		if ($verbose) {
			$mustache = new \Mustache_Engine([ 'logger' => Log::channel('stderr')
			]);
		} else {
			$mustache = new \Mustache_Engine();
		}
		$template = file_get_contents($template_file);

		$metadata = MustacheHelper::metadata($table);
		if ($this->argument('template') == "english") $metadata['language'] = "English";
		if ($this->argument('template') == "french") $metadata['language'] = "French";
		
		$rendered = $mustache->render($template, $metadata);
		if ($verbose && !($this->option('compare') || $install) )
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
		$install = $this->option('install');
		$verbose = $this->option('verbose');
		$pretend = $this->option('pretend');
		
		if (!Schema::tableExists($table)) {
			$this->error("Unknow table $table in tenant database");
			return 1;
		}

		try {
			if ($this->argument('template') == "all") {
				foreach ($this->templates as $tpl) {
					$tpl_file = MustacheHelper::template_file($table, $tpl);
					$result_file = MustacheHelper::result_file($table, $tpl);
					$this->process_file($table, $tpl_file, $result_file);
				}
			} else {
				$template_file = MustacheHelper::template_file($table, $this->argument('template'));
				$result_file = MustacheHelper::result_file($table, $this->argument('template'));
				$this->process_file($table, $template_file, $result_file);
			}
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
			return 1;
		}
		
		if ($this->option('compare')) {
			$comparator = "WinMergeU";
			if ($this->argument('template') == "all") {
				foreach ($this->templates as $tpl) {
					$result_file = MustacheHelper::result_file($table, $tpl);
					$install_file = MustacheHelper::result_file($table, $tpl, true);
					$cmd = "$comparator $result_file $install_file";
					if ($verbose) echo "\ncmd = $cmd";
					
					$returnVar = NULL;
					$output = NULL;										
					if (!$pretend) exec ( $cmd, $output, $returnVar );
				}
			} else {
				$result_file = MustacheHelper::result_file($table, $this->argument('template'));
				$install_file = MustacheHelper::result_file($table, $this->argument('template'), true);
				$cmd = "$comparator $result_file $install_file";
				if ($verbose) echo "\ncmd = $cmd";
				
				$returnVar = NULL;
				$output = NULL;
				if (!$pretend) exec ( $cmd, $output, $returnVar );
			}
		}

		if ($install) {
			if ($this->argument('template') == "all") {
				foreach ($this->templates as $tpl) {
					$result_file = MustacheHelper::result_file($table, $tpl);
					$install_file = MustacheHelper::result_file($table, $tpl, true);
					$cmd = "copy $result_file $install_file";
					if ($verbose) echo "\ncmd = $cmd";
					
					$returnVar = NULL;
					$output = NULL;
					if (!$pretend) copy($result_file, $install_file);
				}
			} else {
				$result_file = MustacheHelper::result_file($table, $this->argument('template'));
				$install_file = MustacheHelper::result_file($table, $this->argument('template'), true);
				$cmd = "copy $result_file $install_file";
				if ($verbose) echo "\ncmd = $cmd";
				
				$returnVar = NULL;
				$output = NULL;
				if (!$pretend) copy ($result_file,  $install_file);
			}
		}
		
		return 0;
	}

}
