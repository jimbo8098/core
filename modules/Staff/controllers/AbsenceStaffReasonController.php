<?php

class AbsenceStaffReasonController implements GibbonCRUDAPI
{
	private $db;

	private $sqlCommands = array(
		'GetAll' => "SELECT * FROM gibbonAbsenceStaffReason;",
		'GetByID' => "SELECT * FROM gibbonAbsenceStaffReason where gibbonAbsenceStaffReasonID = :id"
	);

	function __construct($db)
	{
		$this->db = new DatabaseHelper($db);
	}

	public function GetAll()
	{
		try
		{
			return $this->db->RunSQL($this->sqlCommands['GetAll'],"AbsenceStaffReason",null);
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}

	public function GetByID($id)
	{}
}

?>
