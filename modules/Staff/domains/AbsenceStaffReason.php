<?php
namespace Gibbon\Modules\Staff\Domains;
class AbsenceStaffReason extends \Gibbon\Common\GibbonDomainDeserializer implements \Gibbon\Common\GibbonDataObject
{
	public $gibbonAbsenceStaffReasonID;
	public $shortReason;
	public $description;

	public function __construct($jsonObj = null)
	{
		if($jsonObj != null)
		{
			$this->Deserialize($jsonObj);
		}
	}

	public function CheckValidity()
	{
		if($this->gibbonAbsenceStaffReasonID == null || $this->shortReason == null)
		{
			return false;
		}
		else
		{
			if(strlen($this->shortReason) <= 50)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	public function GetID()
	{
		return $this->gibbonAbsenceStaffReasonID;
	}

	public function GetInsert()
	{
		return array(
			"SQL" => "INSERT INTO gibbonAbsenceStaffReason(shortReason,description) values (:shortReason,:description);",
			"VARS" => array(
				"shortReason" => $this->shortReason,
				"description" => $this->description
			)
		);
	}

	public function GetDelete()
	{
		return array( 
			"SQL" => "DELETE FROM gibbonAbsenceStaffReason WHERE gibbonAbsenceStaffReasonID = :ID;",
			"VARS" => array("ID" => $this->gibbonAbsenceStaffReasonID)
		);
	}

	public function GetUpdate()
	{
		if($this->gibbonAbsenceStaffReasonID != null)
		{
			return array(
				"SQL" => "UPDATE gibbonAbsenceStaffReason SET shortReason = :shortReason,description = :description WHERE gibbonAbsenceStaffReasonID = :ID;",
				"VARS" => array(
					"ID" => $this->gibbonAbsenceStaffReasonID,
					"shortReason" => $this->shortReason,
					"description" => $this->description
				)
			);
		}
	}
}

?>
