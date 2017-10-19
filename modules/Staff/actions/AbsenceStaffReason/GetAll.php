<?php

include '/var/www/gibbon/gibbon.php';
$loader->addNamespace('Gibbon\\Common','common/');
$loader->addNamespace('Gibbon\\Modules\\Staff\\Controllers','modules/Staff/controllers/');
$loader->addNamespace('Gibbon\\Modules\\Staff\\Domains','modules/Staff/domains/');
$loader->register();

$asrCtrlr = new Gibbon\Modules\Staff\Controllers\AbsenceStaffReasonController($connection2);
print json_encode($asrCtrlr->GetAll());

?>
