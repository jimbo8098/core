<?php

class StaffAbsenceController implements GibbonCRUDAPI
{
	private $db;
	function __construct($db)
	{
		$this->db = new DatabaseHelper($db);
	}

	public function GetAll()
	{
		try
		{
			$this->db->RunSQL("SELECT 
		}
	}
}

?>
