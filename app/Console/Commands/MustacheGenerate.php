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
	protected $templates = [ "controller","model","request","index","create","edit","english", "api",
		"test_model","test_controller","test_dusk", "test_api"
	];

	
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'mustache:generate' 
			. ' {--compare : compare generated files with current version}' 
			. ' {--install : install generated files from current version}' 
			. ' {--delete  : delete installed files}'
			. ' {--pretend : simulation, no actions}' 
			. ' {table : database table}' 
					. ' {template :  mustache template, all|controller|model|request|index|create|edit|english|test_model|test_controller|api|test_api|migration}' 
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

	/**
	 * Write a string as a file on the file system
	 * 
	 * @param string $content
	 * @param string $filename

     * @SuppressWarnings("PMD.ShortVariable")
	 */
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
		
		if ($verbose) {
			$msg = "php artisant mustache:generate";
			$msg .= " table=$table";
			$msg .= ", schema=" . ENV('DB_SCHEMA', 'tenanttest');
			$msg .= "\n";
			echo $msg;
		}
		
		if (!Schema::tableExists($table)) {
			$this->error("Unknow table $table in tenant database");
			return 1;
		}

		if ($template == "all") {
			$template_list = $this->templates;
		} else {
			$template_list = [$template];
		}
		
		if ($this->option('delete')) {
			foreach ($template_list as $tpl) {
				$install_file = MustacheHelper::result_file($table, $tpl, true);
				if (file_exists($install_file)) {
					if ($verbose) echo "\ndelete $install_file";
					unlink($install_file);
				} else {
					if ($verbose) echo "\ncannot delete $install_file (not found)";
				}
			}
			return 0;	
		}
		
		// process all templates and generate the result
		try {
			foreach ($template_list as $tpl) {
				$tpl_file = MustacheHelper::template_file($table, $tpl);
				$result_file = MustacheHelper::result_file($table, $tpl);
				$this->process_file($table, $tpl_file, $result_file);
			}
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
			return 1;
		}
		
		if ($this->option('compare')) {
			$comparator = "WinMergeU";
			foreach ($template_list as $tpl) {
				$result_file = MustacheHelper::result_file($table, $tpl);
				$install_file = MustacheHelper::result_file($table, $tpl, true);
				$cmd = "$comparator $result_file $install_file";
				if ($verbose) echo "\ncmd = $cmd";
					
				$returnVar = NULL;
				$output = NULL;										
				if (!$pretend) exec ( $cmd, $output, $returnVar );
			}
		}

		if ($install) {
			foreach ($template_list as $tpl) {
				$result_file = MustacheHelper::result_file($table, $tpl);
				$install_file = MustacheHelper::result_file($table, $tpl, true);
				
				$dir = dirname($install_file);
				if (!is_dir($dir)) {
					mkdir($dir, 0777, true);
				}
				
				$cmd = "copy $result_file $install_file";
				if ($verbose) echo "\ncmd = $cmd";
					
				$returnVar = NULL;
				$output = NULL;
				if (!$pretend) copy($result_file, $install_file);
			}
		}
		
		return 0;
	}

}
