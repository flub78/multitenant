<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Schema;

class IsField implements Rule {

	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct(string $table) {
		$this->table = $table;
	}

	/**
	 * Determine if the validation rule passes.
	 *
	 * @param string $attribute
	 * @param mixed $value
	 * @return bool
	 */
	public function passes($attribute, $value) {
		
		if (!$value) return true;
		
		if (!Schema::tableExists($this->table)) {
			$this->msg = 'Unknown database table ' . $this->table . ' for ' . $attribute;
			return false;
		}
		
		if (!Schema::fieldExists($this->table, $value)) {
			$this->msg = "Unknown field $value in table " . $this->table . ' for ' . $attribute;
			return false;
		}
		return Schema::fieldExists($this->table, $value);
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message() {
		return $this->msg;
	}

}
