<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Schema;

class IsTable implements Rule {

	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->table = "";
	}

	/**
	 * Determine if the validation rule passes.
	 *
	 * @param string $attribute
	 * @param mixed $value
	 * @return bool
	 */
	public function passes($attribute, $value) {
		$this->table = $value;
		$this->attribute = $attribute;
		
		if (!$value) return true;
		return Schema::tableExists($value);
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message() {
		return 'Unknown database table ' . $this->table . ' for field ' . $this->attribute;
	}

}
