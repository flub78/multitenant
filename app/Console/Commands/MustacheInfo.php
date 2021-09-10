<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schema;
use App\Helpers\MustacheHelper;
use App\Models\Tenants\Metadata;

class MustacheInfo extends Command {
	
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'mustache:info' 
			. ' {table : database table}'; 

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Table information for mustache code generation';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Display code generation information about the database table
	 * @param string $table
	 */
	protected function table_info (string $table) {
		echo "Code generation Information\n";
		echo "table = $table\n";
		$field_list = Schema::fieldList($table);
		echo "Fields = " . implode(", ", $field_list) . "\n";
		foreach ($field_list as $field) {
			echo "\t";
			$info = Schema::columnInformation($table, $field);
			foreach (['Field', 'Type', 'Null', 'Key', 'Default', 'Extra'] as $attr) {
				echo "$attr=" . $info->$attr;
				echo ($attr != 'Extra') ? ', ' : '';
			}
			echo "\n";
			$meta = Metadata::where(['table' => $table, "field" => $field])->first();
			if ($meta) {
				echo "\t\tMetadata: ";
				echo "subtype=" . $meta->subtype . ", options='" . $meta->options . "'\n";
			}
		}
	}
	
	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle() {
		$table = $this->argument('table');

		if (!Schema::tableExists($table)) {
			$this->error("Unknow table $table in tenant database");
			return 1;
		}

		$this->table_info($table);

		return 0;
	}

}
