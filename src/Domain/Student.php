<?php
namespace Common\Domain
class Student extends Person
{
	public $EnrolmentID;
	public $YearName;
	public $ShortYearName;
	public $Status;

	function __construct()
	{
		//Student emails and telephone numbers should always be blank, we don't use them
		$this->Callable = false;
		$this->Textable = false;
		$this->Mailable = false;
	}
}

?>
