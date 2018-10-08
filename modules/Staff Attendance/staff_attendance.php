<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

@session_start() ;

use Gibbon\Forms\Form;

//Module includes
include "./modules/" . $_SESSION[$guid]["module"] . "/moduleFunctions.php" ;

if (isModuleAccessible($guid, $connection2)==FALSE) {
	//Acess denied
	print "<div class='error'>" ;
		print "You do not have access to this action." ;
	print "</div>" ;
}
else {
  $form = Form::create('record_attendance', $_SESSION[$guid]['absoluteURL'].'/modules/'.$_SESSION[$guid]['module'].'/staff_attendance.php');
  $form->addRow()->addHeading(__("Record Non-Attendance"));

  $sql = "select
    gs.gibbonStaffID as 'value',
    concat(gp.title,' ',gp.firstName,' ',gp.surname) as 'name'
      from gibbonStaff gs
      inner join gibbonPerson gp on gp.gibbonPersonID = gs.gibbonPersonID;";
  $row = $form->addRow();
    $row->addLabel("attendanceStaffID",__("Staff Member"));
    $row->addSelect("attendanceStaffID")->fromQuery($pdo,$sql)->isRequired();

  $row = $form->addRow();
    $row->addLabel("attendanceStartDate",__("Start Date"));
    $row->addDate("attendanceStartDate")->isRequired();

  $row = $form->addRow();
    $row->addLabel("attendanceEndDate",__("End Date"));
    $row->addDate("attendanceEndDate")->isRequired();

  $sql = "select gibbonAttendanceCodeID as 'value', name from gibbonAttendanceCode where direction = 'Out';";
  $row = $form->addRow();
    $row->addLabel("attendanceReason",__("Reason"));
    $row->addSelect("attendanceReason")->fromQuery($pdo,$sql);

  $form->addRow()->addSubmit();

  echo $form->getOutput();
  echo "<br/>";
  
  $saCtrl = new StaffAttendanceController();
  var_dump($saCtrl->GetAllAttendance());

}	
?>
