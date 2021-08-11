<?php

namespace App\Traits;

trait UserTestTrait {
	
	function initAttributes () {
		$this->name = "Titi Paris";
		$this->email1 = "titi@gmail.com";
		$this->email2 = "titi@free.fr";
		$this->password = "password4titi";
	}
}