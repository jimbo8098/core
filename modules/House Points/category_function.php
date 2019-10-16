<?php
use Gibbon\Forms\Form;

$mode = $_POST['mode'] ?? $_GET['mode'] ?? '';
$categoryID = isset($_POST['categoryID'])? $_POST['categoryID'] : '';
$categoryName = isset($_POST['categoryName'])? trim($_POST['categoryName']) : '';
$categoryType = isset($_POST['categoryType'])? $_POST['categoryType'] : '';
$categoryPresets = isset($_POST['categoryPresets'])? trim($_POST['categoryPresets']) : '';
$returnTo = $_POST['returnTo'] ?? $_GET['returnTo'] ?? '';
$result = 1; //Default fail

switch($mode)
{
    case "edit":
        if($categoryID != 0)
        {
            $hpGateway = $container->get(HousePointsGateway::Class);
            $criteria = $hpGateway->newQueryCriteria()
                ->filterBy('categoryID',$categoryID)
                ->sortBy('categoryOrder','DESC');
            $categories = $hpGateway->queryCategories($criteria,false);
            $result = 0;
        }
        else
        {
            $result = 2;
        }
        break;

    case "add":
        if($categoryType != "House" && $categoryType != "Student") $result = 2;
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
            $result = 0;
        }
        catch(PDOException $e)
        {
            $result = 2;
        }
        break;

    case "delete":
        $sql = 'DELETE FROM hpCategory WHERE categoryID = :categoryID';
        $stmnt = $connection2->prepare($sql);
        $data = null;
        try
        {
            $data = $stmnt->execute(array(
                'categoryID' => $categoryID
            ));
        }
        catch(PDOException $e)
        {
            $result = 2;
        }
        break;

    default:
        $result = 3;
}
echo "Return to: " . $returnTo . "</br>";
echo "Result: " . $result;
//header('Location: ' . $returnTo . '&status=' . $result);
?>