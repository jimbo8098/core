<?php

use Gibbon\Forms\Form;

$categoryID = isset($_POST['categoryID'])? $_POST['categoryID'] : '';
$categoryName = isset($_POST['categoryName'])? trim($_POST['categoryName']) : '';
$categoryType = isset($_POST['categoryType'])? $_POST['categoryType'] : '';
$categoryPresets = isset($_POST['categoryPresets'])? trim($_POST['categoryPresets']) : '';
$returnTo = $_POST['returnTo'] ?? $_GET['returnTo'] ?? '';

$sql = 'DELETE FROM hpCategory WHERE categoryID = :categoryID';
$stmnt = $connection2->prepare($sql);
$result = null;
try
{
    $result = $stmnt->execute(array(
        'categoryID' => $categoryID
    ));
}
catch(PDOException $e)
{
    throw $e;
    header("Location: " . $returnTo . "status=An error occurred whilst deleting category");
}

?>