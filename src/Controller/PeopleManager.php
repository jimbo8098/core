<?php

namespace Common\Controller;
include_once './functions.php';


class PeopleController
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
