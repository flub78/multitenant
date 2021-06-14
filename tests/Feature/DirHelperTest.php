<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Helpers\DirHelper;

/**
 * Tests for the DirHelper class.
 * 
 * As soon as a class need to use storage_path a container is required and it becomes a feature test.
 * 
 * @author frederic
 *
 */
class DirHelperTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_unknown_dir()
    {
        
        DirHelper::rrmdir("");
        DirHelper::rrmdir("unknown_dir");
        $this->assertTrue(true, "no exception raised");
    }
    
    public function test_delete_existing_empty_dir() {
    	
    	$storage = storage_path ();    	
    	$testdir = $storage . DIRECTORY_SEPARATOR . "testdir";
    	
    	// One level empty dir
    	$this->assertFalse(is_dir($testdir), "test directory does not exist");
    	mkdir($testdir);
    	$this->assertTrue(is_dir($testdir), "test directory has been created");
    	DirHelper::rrmdir($testdir);
    	$this->assertFalse(is_dir($testdir), "test directory has been deleted"); 	
    }
    
    public function test_delete_existing_non_empty_dir() {
    	
    	$storage = storage_path ();
    	$testdir = $storage . DIRECTORY_SEPARATOR . "testdir";
    	$subdir1 = $testdir . DIRECTORY_SEPARATOR . "subdir1";
    	$subdir2 = $testdir . DIRECTORY_SEPARATOR . "subdir2";
    	$subdir1 = $testdir . DIRECTORY_SEPARATOR . "subdir1_1";
    	$subdir1_1 = $subdir1 . DIRECTORY_SEPARATOR . "subdir1";
    	
    	// One level empty dir
    	$this->assertFalse(is_dir($testdir), "test directory does not exist");
    	mkdir($subdir1_1, 0755, true);
    	// mkdir($testdir);
    	// mkdir($subdir1);
    	mkdir($subdir2);
    	$this->assertTrue(is_dir($testdir), "test directory has been created");
    	$this->assertTrue(is_dir($subdir1), "test directory has been created");
    	$this->assertTrue(is_dir($subdir2), "test directory has been created");
    	$this->assertTrue(is_dir($subdir1_1), "test directory has been created");
    	
    	DirHelper::rrmdir($testdir);
    	$this->assertFalse(is_dir($testdir), "test directory has been deleted");
    }
    
}
