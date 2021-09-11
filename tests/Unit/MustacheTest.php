<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Just a small test to insure that the mustache templating mechanism is correctly in place
 * and to make some investigation.
 * 
 * @author frederic
 *
 */
class MustacheTest extends TestCase {
		
	const GEN_INSTALLATION = "C:\Users\frederic\Dropbox\xampp\htdocs\multitenant";
	const GEN_TEMPLATES = "C:\Users\frederic\Dropbox\xampp\htdocs\multitenant\build\templates";
	const GEN_RESULTS = "C:\Users\frederic\Dropbox\xampp\htdocs\multitenant\build\results";
		
	public function test_basic_mustache() {
		
		$mustache = new \Mustache_Engine;
		$this->assertNotNull($mustache);
		
		$rendered= $mustache->render('Hello, {{planet}}!', array('planet' => 'World')); // "Hello, World!"
		$this->assertEquals("Hello, World!", $rendered);
	}
	
	public function test_file_mustache() {
		
		$mustache = new \Mustache_Engine;
		$this->assertNotNull($mustache);
		
		echo "\ngetcwd = " . getcwd() . "\n";
	}
	
	
	function getDirContents($dir){
		$results = array();
		$files = scandir($dir);
		
		foreach($files as $key => $value){
			if ($value == '.') continue;
			if ($value == '..') continue;
			$file = $dir . DIRECTORY_SEPARATOR . $value;
			if(!is_dir($file)){
				$results[] = $file;
			} else if(is_dir($file)) {
				$results = array_merge($results, $this->getDirContents($file));
			}
		}
		return $results;
	}
	
	public function test_investigation() {
		$ds = DIRECTORY_SEPARATOR;
		$installation = getcwd();
		$templates =  getcwd() . $ds . 'build' . $ds . 'templates';
		$results =  getcwd() . $ds . 'build' . $ds . 'results';
		echo "\nGEN_TEMPLATES = $templates";
		
		print_r($this->getDirContents($templates));
		$this->assertTrue(true);
	}
}
