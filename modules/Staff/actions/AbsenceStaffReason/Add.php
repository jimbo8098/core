<?php

include '/var/www/gibbon/gibbon.php';
$loader->addNamespace('Gibbon\\Common','common/');
$loader->addNamespace('Gibbon\\Modules\\Staff\\Controllers','modules/Staff/controllers/');
$loader->addNamespace('Gibbon\\Modules\\Staff\\Domains','modules/Staff/domains/');
$loader->register();

$asrCtrlr = new Gibbon\Modules\Staff\Controllers\AbsenceStaffReasonController($connection2);
$postedData = json_decode(file_get_contents('php://input'));
if($postedData != null)
{
	$postedReason = new Gibbon\Modules\Staff\Domains\AbsenceStaffReason($postedData);
	try
	{
		$asrCtrlr->add($postedReason);
		return true;
	}
	catch(Exception $e)
	{
		return json_encode($e);
	}
}
else
{
	$e = new Gibbon\Modules\Staff\Common\ActionException();
	$e->Reason = 'Posted data was null';
	$e->SetEnum("BAD_DATA");
	throw $e;
}

?>
