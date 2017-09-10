<?php

include_once './functions.php';
class AttendanceDataSetManager
{
	private $DB;

	function __construct()
	{
		$sqlConn = new Gibbon\sqlConnection();
		$this->DB = $sqlConn->getConnection();
	}

	function GetStaffAttendance()
	{
		$stmnt = $this->DB->prepare("select 'test';");
		$stmnt->execute();
		$d = $stmnt->fetchAll();
		var_dump($d);
	}

	//Accepts AttendanceType
	function SetStaffAttendance($attEntry)
	{
		if($attEntry != null)
		{
			if(gettype($attEntry) == "object")
			{
				if(get_class($attEntry) == "AttendanceEntry")
				{
				}
			}
		}
	}

	function AddStaffAttendance($attEntry)
	{
		return "Woot";
	}
}

?>
