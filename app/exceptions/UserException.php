<?php

namespace App\Main;


/**
 * Class ClassNotFoundException
 * @package App\Main
 */
class UserException extends \Exception
{
	/**
	 * UserException constructor.
	 *
	 * @param null $message
	 * @param int $code
	 */
	public function __construct($message = null, $code = 0)
	{
		parent::__construct($message, $code);
		error_log($this->getTraceAsString(), 3,
			EXCEPTION_LOG);
	}

	/**
	 * String representation of the exception
	 * @link http://php.net/manual/en/exception.tostring.php
	 * @return string the string representation of the exception.
	 * @since 5.1.0
	 */
	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}

	/**
	 * @param string $message
	 */
	public function saveMessage(string $message) {
		echo "A custom function for this type of exception\n";
	}
}