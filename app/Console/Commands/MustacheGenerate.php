<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schema;
use App\Helpers\MustacheHelper;


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
	protected function process_file(string $table, string $template, string $result) {
		echo "\nprocessing $table $template";
		
		// We need to generate the template filename, the result filename and the installation filename
		$template_filename = MustacheHelper::template_filename($template);
		$result_filename = MustacheHelper::result_filename($result);
		// $installation_filename = MustacheHelper::installation_filename($template);
		
		foreach (["table", "template", "template_filename", "result_filename"] as $name) {
			echo "\n$name = " . "${$name}"; 
		}
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
			$filename = MustacheHelper::template_filename($template);
			if (!$filename) {
				$this->error("Template $template not found");
				return 1;
			}
		}

		$this->process_file($table, $template, $result);

		return 0;
	}

}
