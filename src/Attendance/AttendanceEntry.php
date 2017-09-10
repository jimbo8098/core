<?php


namespace Common\Attendance;
include 'AttendanceEntryException.php';

class AttendanceEntry
{
	public $PersonType;
	public $AttendanceType;
	public $Person;
	public $Date;

	function __construct()
	{
	}

	function SetPerson($personObj)
	{
		if(gettype($personObj) == "object")
		{
			switch(get_class($personObj))
			{
				case "Student":
					$this->PersonType = "Student";
					$this->Person = $personObj;
					return true;

				case "StaffMember":
					$this->PersonType = "StaffMember";
					$this->Person = $personObj;
					return true;

				default:
					throw new AttendanceEntryException("The person object is not recognised. Object provided was of type [" . get_class($personObj) . "]");
			}
		}
		else
		{
			throw new AttendanceEntryException("The person object was not an object");;
		}
	}

	function SetDate($date)
	{
		$this->Date = $date;
	}
}

?>
