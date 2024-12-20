<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schema;
use App\Helpers\MustacheHelper;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Helpers\MetadataHelper as Meta;
use Illuminate\Support\Facades\Artisan;

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

	// The tool also support special templates migration and doc

	protected $templates = [
		"controller",
		"model",
		"request",
		"index",
		"create",
		"edit",
		"show",
		"english",
		"api",
		"factory",
		"test_model",
		"test_controller",
		"test_dusk",
		"test_api"
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
		. ' {template :  mustache template, all|controller|model|request|index|create|edit|show|english|test_model|test_controller|api|test_api|migration}'
		. '';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate code with a mustache template';

	protected $metadata = null;

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

	protected function check_db_connection() {
		$connection = env('DB_CONNECTION', 'mysql');
		try {
			\DB::connection()->getPDO();
			return \DB::connection()->getDatabaseName();
		} catch (\Exception $e) {
			return null;
		}
	}

	/**
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
			$mustache = new \Mustache_Engine([
				'logger' => Log::channel('stderr')
			]);
		} else {
			$mustache = new \Mustache_Engine();
		}
		$template = file_get_contents($template_file);

		// $metadata = MustacheHelper::metadata($table);
		if ($this->argument('template') == "english") $metadata['language'] = "English";

		$rendered = $mustache->render($template, $this->metadata);
		if ($verbose && !($this->option('compare') || $install))
			echo $rendered;

		$this->write_file($rendered, $result_file);
	}

	/**
	 * Display the documentation (which is a template)
	 *
	 * @param string $table
	 * @param string $template
	 */
	protected function process_doc(string $table) {

		$template_file = MustacheHelper::template_file($table, 'doc');

		$mustache = new \Mustache_Engine();

		$template = file_get_contents($template_file);

		$metadata = MustacheHelper::metadata($table);

		$rendered = $mustache->render($template, $metadata);
		echo $rendered;
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
		$element = Meta::element($table);

		if ($verbose) {
			$msg = "php artisant mustache:generate";
			$msg .= " table=$table";
			$msg .= ", schema=" . ENV('DB_SCHEMA', 'tenanttest');
			$msg .= ", template=" . $template;
			$msg .= "\n";
			echo $msg;
		}

		if (!$this->check_db_connection()) {
			$this->error("No connection to database " . env("DB_SCHEMA", 'tenanttest'));
			return 1;
		} else {
			if ($verbose) $this->info("Connected to database " . $this->check_db_connection());
		}

		if (!Schema::tableExists($table)) {
			$this->error("Unknown table $table in " . ENV('DB_SCHEMA', 'tenanttest') . " database");
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
					# echo "\ndelete $install_file";
					unlink($install_file);
				} else {
					echo "\ncannot delete $install_file (not found)";
				}
			}
			if ($template == "all") {
				$translation = "resources/lang/fr/$element.php";
				# echo "\ndelete $translation";
				if (file_exists($translation)) unlink($translation);
			}
			return 0;
		}

		// process all templates and generate the result
		$this->metadata = MustacheHelper::metadata($table);
		try {
			foreach ($template_list as $tpl) {
				if ($verbose) echo "processing $tpl\n";
				$tpl_file = MustacheHelper::template_file($table, $tpl);
				$result_file = MustacheHelper::result_file($table, $tpl);
				$this->process_file($table, $tpl_file, $result_file);
			}
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
			return 1;
		}

		if ($this->option('compare')) {
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				$comparator = "WinMergeU";
			} else {
				$comparator = "kdiff3";
			}
			foreach ($template_list as $tpl) {
				$result_file = MustacheHelper::result_file($table, $tpl);
				$install_file = MustacheHelper::result_file($table, $tpl, true);
				$cmd = "$comparator $result_file $install_file";
				if ($verbose) echo "\ncmd = $cmd";

				$returnVar = NULL;
				$output = NULL;
				if (!$pretend) exec($cmd, $output, $returnVar);
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

			if ($template == "all") {
				// Special case of global install

				// translate the English strings
				$exitCode = Artisan::call("mustache:translate --install $element");
				if ($exitCode) {
					echo "\nError while generating French translation $exitCode\n";
				} else {
					if ($verbose) echo "\nFrench translation: resources/lang/fr/$element.php\n";
				}

				// And display information about the rest
				$this->process_doc($table);
			}
		}

		return 0;
	}
}
