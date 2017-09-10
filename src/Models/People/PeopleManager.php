<?php

include_once './functions.php';

class PeopleManagerException
{
	public $Message;
	public $Code;
	public $Data;

	function __construct($mess,$code,$data)
	{
		$this->Message = $mess;
		$this->Code  = $code;

		$pdo = new Gibbon\sqlConnection();
		$DB = $pdo->getConnection();
		switch(getSettingByScope($DB, 'System', 'installType', true))
		{
			case "Testing":
				$this->Data = "You are in testing mode.";
				break;

			default:
				$this->Data = "No extra data because the system is not in a mode supported for that. Your type is set to [" . getSettingByScope($DB, 'System', 'installType', true) . "]";
				break;
		}
	}
}

class PeopleManager
{
	private $DB;

	function __construct()
	{
		$pdo = new Gibbon\sqlConnection();
		$this->DB = $pdo->getConnection();
	}

	public function GetPersonAsStaff($id)
	{
		if($id != null)
		{
			if(gettype($id) == "string")
			{
				$personStmnt = $this->DB->prepare("
					SELECT
						*
					FROM
						gibbonPerson
					WHERE
						gibbonPersonID = '0000000144'
					;
				");

				$personStmnt->execute();
				$people = $personStmnt->fetchAll(PDO::FETCH_CLASS,"StaffMember");
				if(sizeof($people) == 1)
				{
					//Good, we only got one StaffMemeber
					return $people[0];
				}
				else
				{
					throw new PeopleManagerException("Failed to get staff member because the ID matched more than one person",1);
				}

			}
			else
			{
				
			}
		}
	}
}

?>
