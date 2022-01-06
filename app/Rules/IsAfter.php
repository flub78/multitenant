<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Facades\Log;


class IsAfter implements Rule {

	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct($start, $start_time, $end_time) {
		$this->start = $start;
		$this->start_time = $start_time;
		$this->end_time = $end_time;
		$this->error_msg = "";
	}

	/**
	 * Determine if the validation rule passes.
	 * 
	 * There are 4 parameters
	 *    - start, a date in local format, required 
	 *    - start_time, a time, may be empty
	 *    - end, a date in local format, required 
	 *    - end_time, a time, may be empty
	 *
	 * @param string $attribute
	 * @param mixed $value
	 * @return bool
	 */
	public function passes($attribute, $value) {

		$msg = "IsAfter validation rule\n";
		$msg .= "start=" .  $this->start . "\n";
		$msg .= "start_time=" . $this->start_time . "\n";
		$msg .= "$attribute=$value\n";
		$msg .= "end_time=" . $this->end_time . "\n";
		Log::debug($msg);
		
		return true;
		
		$start_str = $this->start;
		if ($this->start_time) {
			$start_str .= ' ' . $this->start_time;
		}
		$end_str = $value;
		if ($this->end_time) {
			$end_str .= ' ' . $this->end_time;
		}
		
		try {
			$cstart = Carbon::parse($start_str);
		} catch (InvalidFormatException $e) {
			$this->error_msg = "Incorrect start date time format $start_str";
			return false;
		}
		
		try {			
			$cend = Carbon::parse($end_str);
		} catch (InvalidFormatException $e) {
			$this->error_msg = "Incorrect end date time format $end_str";
			return false;
		}
		
		$this->error_msg = "end time should be after start time";
		
		return $cend->greaterThanOrEqualTo($cstart);
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message() {
		return $this->error_msg;
	}

}
