<?php

namespace Common\Attendance;
class AttendanceEntryException extends \Exception
{
	public $Message;

	function __construct($mess)
	{
		$this->Message = $mess;
	}
}

?>
