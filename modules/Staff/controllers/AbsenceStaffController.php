<?php
namespace Gibbon\Modules\Staff\Controllers;

class AbsenceStaffController implements \Gibbon\Common\GibbonCRUDAPI
{
	private $db;
	private $sqlCommands = array(
		"GetAll" => "SELECT * FROM gibbonAbsenceStaff;",
		"GetByID" => "SELECT * FROM gibbonAbsenceStaff WHERE gibbonAbsenceStaffID = :ID;"
	);

	function __construct($db)
	{
		$this->db = new \Gibbon\Common\DatabaseHelper($db);
	}

	public function GetAll()
	{
		try
		{
			return $this->db->RunSQL($this->sqlCommands['GetAll'],"\\Gibbon\\Modules\\Staff\\Domains\\AbsenceStaff",null);
		}
		catch(\Exception $e)
		{
			throw $e;
		}
	}

	public function GetByID($id)
	{
		try
		{
			return $this->db->RunSQL($this->sqlCommands['Get'],"\\Gibbon\\Modules\\Staff\\Domains\\AbsenceStaff",null);
		}
		catch(\Exception $e)
		{
			throw $e;
		}
	}

	public function Add($obj)
	{
		try
		{
			return $this->db->InsertSQL($obj->GetInsert());
		}
		catch(\Exception $e)
		{
			throw $e;
		}
	}

	public function Edit($obj)
	{
		try
		{
			return $this->db->UpdatesSQL($obj->GetUpdate());
		}
		catch(\Exception $e)
		{
			throw $e;
		}
	}

	public function Delete($obj)
	{
		try
		{
			return $this->db->DeleteSQL($obj->GetDelete());
		}
		catch(\Exception $e)
		{
			throw $e;
		}
	}
}

?>
