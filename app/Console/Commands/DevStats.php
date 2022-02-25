<?php

namespace App\Console\Commands;

use App\Helpers\TenantHelper;
use Illuminate\Console\Command;

/**
 * An artisan command to list the backups stored on local storage
 * Supports central and tenant applications
 *
 * @author frederic
 *        
 */
class DevStats extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'dev:stats';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Statistics on Github commits';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle() {
		$cmd = "git --no-pager log --decorate=short --pretty=oneline";

		$returnVar = NULL;
		$output = NULL;

		exec($cmd, $output, $returnVar);

		$keywords = [ 
			"Bug fixes" => [ "fix","bug", "Fix", "workaround"],
			"DevOps" => [ "jenkins", "ansible"],
			"Tests" => [ "test", "usk", "Test", "overage", "hpunit", "assert"],
			"Refactoring" => [ "refactoring", "comment", "PMD", "nsible", "leanup"],
			"Architecture" => [ "architecture", "elper", "atatable"],
			"Tenants" => [ "tenant"],
			"Backups" => [ "ackup"],
			"Users" => [ "user", "User", "assword"],
			"Calendar" => [ "calendar", "start_time", "event", "start time", "isAfter"],
			"Authentication" => [ "authent","login", "anctum"],
			"Attachements" => [ "attach"],
			"Code generation" => [ "code","generato", "metadata", "Metadata", "ustache", "schema"],
			"REST API" => [ "rest","api", "API"],
			"Localization" => ["ocalization", "ranslat", "lang"],
			"Configuration" => ["onfig"],
			"Roles" => ["Role", "role"]
		];

		$numbers = [ ];

		// Initialize
		$count = count($output);
		foreach ($output as $line) {
			foreach ($keywords as $key => $val) {
				$numbers [$key] = 0;
			}
		}

		// COunt matches
		$not_classified = 0;
		foreach ($output as $line) {

			$matches = [];
			foreach ($keywords as $key => $val) {
				$key_match = false;
				
				if (str_contains($line, $key)) {
					$key_match = true;
				}
				foreach ($val as $pattern) {
					if (str_contains($line, $pattern))
						$key_match = true;
				}
				if ($key_match) {
					$numbers [$key] = $numbers [$key] + 1;
					$matches[] = $key;
				}
			}
			
			if (count($matches)) {
				// echo implode(', ', $matches) . $line . "\n";
			} else {
				echo "no match : $line\n";
				$not_classified++;
			}
		}

		// Display results
		echo "Commits = $count\n";		
		foreach ($keywords as $key => $val) {
			$percent = number_format(100 * $numbers [$key] / $count, 2);
			echo "$key : $percent %\n";
		}
		echo "not classified = " . number_format(100 *  $not_classified / $count, 2) . " %\n";
        return 0;
    }
}
