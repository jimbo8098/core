<?php

namespace Common\Exception;
class AttendanceEntryException extends \Exception
{
	public $Message;

	function __construct($mess)
	{
		$this->Message = $mess;
	}
}

?>
