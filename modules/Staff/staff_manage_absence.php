<?php

include './gibbon.php';
include 'common/GibbonCRUDAPI.php';
include 'common/DatabaseHelper.php';
include 'domains/AbsenceStaffReason.php';
include 'domains/AbsenceStaff.php';
include 'controllers/AbsenceStaffReasonController.php';

$ar = new AbsenceStaffReasonController($connection2);
var_dump($ar->GetAll());

?>
