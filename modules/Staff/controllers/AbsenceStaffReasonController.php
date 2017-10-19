<?php
namespace Gibbon\Modules\Staff\Controllers;

class AbsenceStaffReasonController implements \Gibbon\Common\GibbonCRUDAPI
{
	private $db;

	private $sqlCommands = array(
		'GetAll' => "SELECT * FROM gibbonAbsenceStaffReason;",
		'GetByID' => "SELECT * FROM gibbonAbsenceStaffReason where gibbonAbsenceStaffReasonID = :ID"
	);

	function __construct($db)
	{
		$this->db = new \Gibbon\Common\DatabaseHelper($db);
	}

	public function GetAll()
	{
		try
		{
			return $this->db->RunSQL($this->sqlCommands['GetAll'],"Gibbon\\Modules\\Staff\\Domains\\AbsenceStaffReason",null);
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}

	public function GetByID($id)
	{
		try
		{
			return $this->db->RunSQL($this->sqlCommands['GetByID'],"Gibbon\\Modules\\Staff\\Domains\\AbsenceStaffReason",array("ID" => $id));
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function Add($record)
	{
		try
		{
			return $this->db->InsertSQL($record->GetInsert());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function Edit($record)
	{
		try
		{
			return $this->db->UpdateSQL($record->GetUpdate());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function Delete($record)
	{
		try
		{
			return $this->db->DeleteSQL($record->GetDelete());
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
}

?>
