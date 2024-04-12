<?php

namespace App\Exceptions;
use Illuminate\Support\Arr;

use Exception;

class ModelException extends Exception
{
    private $_errors;

	public function __construct($errors, $code = 0, Exception $previous = null)
	{
		$this->_errors = $errors;
		if(!is_array($errors))
			$this->_errors = array($errors);

		parent::__construct(head(Arr::flatten($this->_errors)), $code, $previous);
	}

	public function getErrors() { return $this->_errors; }
}
