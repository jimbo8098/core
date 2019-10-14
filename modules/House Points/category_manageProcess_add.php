<?php

$categoryID = isset($_POST['categoryID'])? $_POST['categoryID'] : '';
$categoryName = isset($_POST['categoryName'])? trim($_POST['categoryName']) : '';
$categoryType = isset($_POST['categoryType'])? $_POST['categoryType'] : '';
$categoryPresets = isset($_POST['categoryPresets'])? trim($_POST['categoryPresets']) : '';
$returnTo = $_POST['returnTo'] ?? $_GET['returnTo'] ?? '';

if($categoryType != "House" && $categoryType != "Student") header('Location: ' . $returnTo . '&status=2'); exit();
$sql = "
    INSERT INTO hpCategory
    SET
        categoryName = :categoryName,
        categoryType = :categoryType,
        categoryPresets = :categoryPresets
    ";
try
{
    $stmnt = $connection2->prepare($sql);
    $stmnt->execute(array(
        "categoryName" => $categoryName,
        "categoryType" => $categoryType,
        "categoryPresets" => $categoryPresets
    ));
    header('Location: ' . $returnTo . '&status=0');
    exit();
}
catch(PDOException $e)
{
    throw $e;
    header('Location: ' . $returnTo . '&status=1');
    exit();
}
?>