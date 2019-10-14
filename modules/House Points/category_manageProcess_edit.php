<?php

use Gibbon\Module\HousePoints\Domain\HousePointsGateway;

$categoryID = isset($_POST['categoryID'])? $_POST['categoryID'] : 0;
$categoryName = isset($_POST['categoryName'])? trim($_POST['categoryName']) : '';
$categoryType = isset($_POST['categoryType'])? $_POST['categoryType'] : '';
$categoryPresets = isset($_POST['categoryPresets'])? trim($_POST['categoryPresets']) : '';
$returnTo = $_POST['returnTo'] ?? $_GET['returnTo'] ?? '';

$result = 1; //Default fail
if($categoryID != 0)
{
    $hpGateway = $container->get(HousePointsGateway::Class);
    $criteria = $hpGateway->newQueryCriteria()
        ->filterBy('categoryID',$categoryID)
        ->sortBy('categoryOrder','DESC');
    $categories = $hpGateway->queryCategories($criteria,false);
    var_dump($categories);

}
else
{
    $result = 2;
}

header('Location: ' . $returnTo . '&status=' . $result);
?>