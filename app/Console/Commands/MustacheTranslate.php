<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\MustacheHelper;
use App\Helpers\TranslationHelper as Translate;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Translate language files from English to another language (French by default).
 * 
 * @author frederic
 *
 */
class MustacheTranslate extends Command {
	protected $templates = [ "lang"];

	/*
	 * "test_model","test_controller","test_dusk"
	 */
	
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'mustache:translate' 
			. ' {--compare : compare generated files with current version}' 
			. ' {--install : install generated files from current version}' 
			. ' {--pretend : simulation, no actions}' 
			. ' {--lang=fr : target language}' 
			. ' {file : database table}' 
			. ' {template=translation :  mustache template}' 
			. '';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Translate an English language file into another language';

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
	 * Translate one file
	 *
	 * @param string $lang
	 * @param string $source_file
	 * @param string $template_file
	 * @param string $result_file
	 */
	protected function translate_file(string $lang, string $source_file, string $template_file, string $result_file, $data = []) {
		$verbose = $this->option('verbose');
		$install = $this->option('install');
		if ($verbose) {
			echo "\ntranslating source=$source_file\n" . "template=$template_file\n" . "result=$result_file\n" . "lang=$lang\n";
		}

		if ($verbose) {
			$mustache = new \Mustache_Engine([ 'logger' => Log::channel('stderr')
			]);
		} else {
			$mustache = new \Mustache_Engine();
		}
		$template = file_get_contents($template_file);
		$source = file_get_contents($source_file);
		$source = str_replace('<?php', '', $source);
		
		$data['language'] = $lang;
		
		$hash = eval($source);
		$translated_hash = Translate::translate_array($hash, $lang);
		
		$data['translated'] = Translate::pretty_print($translated_hash, 1);
 		
		$rendered = $mustache->render($template, $data);
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
		$file = $this->argument('file');
		$lang = $this->option ( 'lang' );
		$template = $this->argument('template');
		$install = $this->option('install');
		$verbose = $this->option('verbose');
		$pretend = $this->option('pretend');
		

		try {
			$template_file = MustacheHelper::template_file($file . 's', $template);
			$result_file = MustacheHelper::translation_result_file($file, $lang);
			$source_file = MustacheHelper::source_language_file($file);
			$this->translate_file($lang, $source_file, $template_file, $result_file, ['file' => $file]);
			
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
			return 1;
		}
		
		if ($this->option('compare')) {
			$comparator = "WinMergeU";

			$result_file = MustacheHelper::translation_result_file($file, $lang);
			$install_file = MustacheHelper::translation_result_file($file, $lang, true);
			$cmd = "$comparator $result_file $install_file";
			if ($verbose) echo "\ncmd = $cmd";
				
			$returnVar = NULL;
			$output = NULL;
			if (!$pretend) exec ( $cmd, $output, $returnVar );
			
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
