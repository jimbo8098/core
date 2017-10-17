<?php

class AbsenceStaffReason
{
	public $gibbonAbsenceStaffReasonID;
	public $shortReason;
	public $description;

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
}

?>
