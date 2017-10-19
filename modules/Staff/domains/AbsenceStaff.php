<?php
namespace Gibbon\Modules\Staff\Domains;
class AbsenceStaff extends \Gibbon\Common\GibbonDomainDeserializer implements \Gibbon\Common\GibbonDataObject
{
	public $gibbonAbsenceStaffID;
	public $start;
	public $end;
	public $absentPerson;
	public $reason;
	public $comment;
	public $coverPerson;

	public function __construct($json = null)
	{
		if($json != null)
		{
			$this->Deserialize($json);
		}
	}

	public function GetID()
	{
		return $this->gibbonAbsenceStaffID;
	}

	public function GetInsert()
	{
		return array(
			"SQL" => "INSERT INTO gibbonAbsenceStaff(start,end,absentPerson,reason,comment,coverPerson) VALUES (:start,:end,:absentPerson,:reason,:comment,:coverPerson);",
			"VARS" => array(
				"start" => $this->start,
				"end" => $this->end,
				"absentPerson" => $this->absentPerson,
				"reason" => $this->reason,
				"comment" => $this->comment,
				"coverPerson" => $this->coverPerson
		)
		);
	}

	public function GetDelete()
	{
		return array(
			"SQL" => "DELETE FROM gibbonAbsenceStaff WHERE gibbonAbsenceStaffID = :ID;",
			"VARS" => array(
				"ID" => $this->GetID()
			)
		);
	}

	public function GetUpdate()
	{
		return array(
			"SQL" => "UPDATE gibbonAbsenceStaff SET start = :start, end = :end, absentPerson = :absentPerson, reason = :reason, comment = :comment, coverPerson = :coverPerson WHERE gibbonAbsenceStaffID = :ID;",
			"VARS" => array(
				"ID" => $this->GetID(),
				"start" => $this->start,
				"end" => $this->end,
				"absentPerson" => $this->absentPerson,
				"reason" => $this->reason,
				"comment" => $this->comment,
				"coverPerson" => $this->coverPerson
			)
		);
	}
}

?>
