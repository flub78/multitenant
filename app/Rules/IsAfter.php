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
	 * - start, a date in local format, required
	 * - start_time, a time, may be empty
	 * - end, a date in local format, required
	 * - end_time, a time, may be empty
	 *
	 * @param string $attribute
	 * @param mixed $value
	 * @return bool
	 */
	public function passes($attribute, $value) {
		$this->end = $value;
		
		$msg = "IsAfter validation rule\n";
		$msg .= "start=" . $this->start . "\n";
		$msg .= "start_time=" . $this->start_time . "\n";
		$msg .= "$attribute=$value\n";
		$msg .= "end_time=" . $this->end_time . "\n";
		Log::debug($msg);

		// Create Carbon start
		try {
			if ($this->start_time) {
				$cstart = Carbon::createFromFormat(__('general.datetime_format'), $this->start . ' ' . $this->start_time);
			} else {
				$cstart = Carbon::createFromFormat(__('general.date_format'), $this->start);
			}
		} catch (InvalidFormatException $e) {
			// difficult to unit test because format errors are likely caught by individual field validation
			$this->error_msg = "Incorrect start date time format " . $this->start . ' ' . $this->start_time;
			Log::debug("IsAfter: " . $this->error_msg);
			return false;
		}

		// Create Carbon end
		try {
			if ($this->end_time) {
				$cend = Carbon::createFromFormat(__('general.datetime_format'), $this->end . ' ' . $this->end_time);
			} else {
				$cend = Carbon::createFromFormat(__('general.date_format'), $this->end);
			}
		} catch (InvalidFormatException $e) {
			// difficult to unit test because format errors are likely caught by individual field validation
			$this->error_msg = "Incorrect end date time format " . $this->end . ' ' . $this->end_time;
			Log::debug("IsAfter: " . $this->error_msg);
			return false;
		}
		
		// Compare them
		if (!$cend->greaterThanOrEqualTo($cstart)) {
			$this->error_msg = "end time should be after start time";
			Log::debug("IsAfter: " . $this->error_msg);
			return false;
		}

		Log::debug('IsAfter OK');

		return true;
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
