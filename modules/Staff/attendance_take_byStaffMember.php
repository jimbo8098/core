<?php

@session_start();

//Module includes
include './modules/' . $_SESSION[$guid]['module'] . '/moduleFunctions.php';
include './src/Domain/AttendanceEntry.php';
include './src/Controller/AttendanceController.php';
include './src/Domain/StaffMember.php';
include './src/Controller/PeopleController.php';

$am = new Common\Controller\AttendanceController();
$pm = new Common\Controller\PeopleController();
$sm = $pm->GetPersonAsStaff("0000000144"); 
$ae = new Common\Domain\AttendanceEntry();
try
{
	$ae->SetPerson($sm);
}
catch(Exception $e)
{
	var_dump($e);
}

var_dump($am->AddStaffAttendance($ae));

?>
