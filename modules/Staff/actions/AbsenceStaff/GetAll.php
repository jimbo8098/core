<?php

include '/var/www/gibbon/gibbon.php';
$loader->addNamespace('Gibbon\\Common','common/');
$loader->addNamespace('Gibbon\\Modules\\Staff\\Controllers','modules/Staff/controllers/');
$loader->addNamespace('Gibbon\\Modules\\Staff\\Domains','modules/Staff/domains/');
$loader->register();

$asController = new Gibbon\Modules\Staff\Controllers\AbsenceStaffController($connection2);
$postedData = json_decode(file_get_contents('php://input'));
	$postedAbsence = new Gibbon\Modules\Staff\Domains\AbsenceStaff($postedData);	
	try
	{
		print json_encode($asController->GetAll());
	}
	catch(\Exception $e)
	{
		print json_encode($e);
	}

?>
