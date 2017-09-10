<?php

include_once './functions.php';
class Person
{
	public $gibbonPersonID;
	public $title;
	public $firstName;
	public $surname;
	public $preferredName;
	public $officialName;
	public $nameInCharacters;
	public $gender;
	public $username;
	public $password;
	public $passwordStrong;
	public $passwordStrongSalt;
	public $passwordForceReset;
	public $status;
	public $canLogin;
	public $gibbonRoleIDPrimary;
	public $gibbonRoleIDAll;
	public $dob;
	public $email;
	public $emailAlternate;
	public $image_240;
	public $lastIPAddress;
	public $lastTimestamp;

	public function isMailImportable()
	{
		if($Mailable == true) {return true;} else { return false;}
	}

	public function isTelephoneImportable()
	{
		if($Callable == true && $Textable == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	
}

?>
