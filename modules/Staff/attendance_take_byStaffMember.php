<?php

@session_start();

//Module includes
include './modules/' . $_SESSION[$guid]['module'] . '/moduleFunctions.php';
include './src/Attendance/AttendanceEntry.php';
include './src/Attendance/AttendanceDataSetManager.php';
include './src/Models/People/StaffMember.php';
include './src/Models/People/PeopleManager.php';

$am = new AttendanceDataSetManager();
$pm = new PeopleManager();
$sm = $pm->GetPersonAsStaff("0000000144"); 
$ae = new Common\Attendance\AttendanceEntry();
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
