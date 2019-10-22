<?php
use Gibbon\Forms\Form;
use Gibbon\Module\HousePoints\Domain\HousePointsGateway;

$mode = $_POST['mode'] ?? $_GET['mode'] ?? '';
$categoryID = isset($_POST['categoryID'])? $_POST['categoryID'] : '';
$categoryName = isset($_POST['categoryName'])? trim($_POST['categoryName']) : '';
$categoryType = isset($_POST['categoryType'])? $_POST['categoryType'] : '';
$subCategoryString = isset($_POST['subCategories'])? trim($_POST['subCategories']) : '';
$categoryOrder = isset($_POST['categoryOrder'])?trim($_POST['categoryOrder']) : '';
$returnTo = $_POST['returnTo'] ?? $_GET['returnTo'] ?? '';

$result = 1; //Default fail
if($categoryOrder > 0)
{
    $categoryOrder--; //Category order is zero based in dbso decrement the user-readable version
}
$subCats =[]; //Blank subcategory array for use later
if($subCategoryString != '')
{
    $subCats = array_map(function($sub){
        $exploded = explode(':',$sub);
        if(sizeof($exploded) == 2)
        {
            return [
                'subCategoryName' => trim($exploded[0]),
                'subCategoryValue'=> trim($exploded[1])
            ];
        }
    },explode(',',$subCategoryString));
    $subCats = array_filter($subCats); //Remove null values from the array, these couldn't be parsed
}

function cleanupCategoryOrder($conn)
{
    $sql = "SET @row_number = 0;
    UPDATE hpCategory c
    INNER JOIN (
        SELECT
            (@row_Number := @row_number + 1) - 1 as newCategoryOrder,
            categoryID
        FROM hpCategory
        ORDER BY categoryOrder ASC, categoryID ASC
    ) _c  ON _c.categoryID = c.categoryID
    SET
            c.categoryOrder = _c.newCategoryOrder
    ;";
    try
    {
        $stmnt = $conn->prepare($sql)->execute();
    }
    catch(PDOException $e)
    {
        throw $e;
    }
}

/*
    Joins a list of subCategories to a given categoryID
*/
function mergeSubCategories($conn,$categoryID,$subCategories)
{
    $addCats = [];
    $sql = "SELECT GROUP_CONCAT(subCategoryId,',') as subCategoryIDs, name ,value, count(name) as 'duplicates'  FROM hpSubCategory WHERE categoryID = :categoryID GROUP BY name,value;";
    $stmnt = $conn->prepare($sql);
    $stmnt->execute([
        "categoryID" => $categoryID
    ]);
    $results = $stmnt->fetchAll();
    foreach($subCategories as $subCat)
    {
        //Make sure we haven't added the category within this transaction
        if(array_search($subCat['subCategoryName'],$addCats)) throw new Exception("Continuing would result in duplicate subcategories");
        array_push($addCats,$subCat['subCategoryName']);

        $resNames = array_map(function($res) {return $res['name'];},$results);
        $existingSubCatKey = array_search($subCat['subCategoryName'],$resNames);
        if($existingSubCatKey === false) //Doesn't exist yet
        {
            $sql = "
                INSERT INTO hpSubCategory (
                    categoryID,
                    name,
                    value
                )
                VALUES
                (
                    :categoryID,
                    :subCategoryName,
                    :subCategoryValue
                );
            ";
            $data = [
                "categoryID" => $categoryID,
                "subCategoryName" => $subCat['subCategoryName'],
                "subCategoryValue" => $subCat['subCategoryValue']
            ];
            $stmnt = $conn->prepare($sql);
            $stmnt->execute($data);            
        }
        else if($existingSubCatKey >= 0) //exists in the subcat table already
        {
            $existingSubCat = $results[$existingSubCatKey];
            
            //We only want to add the subcat if it doesn't already exist
            if($existingSubCat['duplicates'] == 1)
            {
                //If it does match, we don't add it unless the value is different. Then we edit the existing value
                $subCategoryID = explode(',',$existingSubCat['subCategoryIDs'])[0]; //Get the first ID in the column, should be the only id in the column
                $sql = "
                    UPDATE hpSubCategory
                    SET
                        value = :subCategoryValue
                    WHERE
                        subCategoryID = :subCategoryID
                ";
                $conn->prepare($sql)->execute([
                    'subCategoryID' => $subCategoryID,
                    'subCategoryValue' => $existingSubCat['value']
                ]);
            }
            else
            {
                throw new Exception("Duplicate subcategories exist named [" . $existingSubCat['name'] . "]");
            }
        }
    }
}

switch($mode)
{
    case "edit":
        if($categoryID != 0)
        {
            $hpGateway = $container->get(HousePointsGateway::Class);
            $criteria = $hpGateway->newQueryCriteria()
                ->filterBy('categoryID',$categoryID)
                ->sortBy('categoryOrder','ASC');
            $categories = $hpGateway->queryCategories($criteria,false,false)->toArray();
            switch(sizeof($categories))
            {
                case 0: $result = 3; break;
                case 1: 
                    switch(strtolower($categoryOrder))
                    {
                        case "top":
                            //Set the order to 0 then increment the others
                            $categoryOrder = 0;
                            $sql = "UPDATE hpCategory SET categoryOrder = categoryOrder + 1;";
                            try{
                                $connection2->prepare($sql)->execute();
                            }
                            catch(PDOException $e)
                            {
                                throw $e;
                            }
                            break;
                        
                        case "bottom":
                            $highestCatOrdinal = $hpGateway->queryUsedCategoryOrders('DESC')->toArray()[0]['value'];
                            $categoryOrder = $highestCatOrdinal + 1; //The ordinal becomes the next highest
                            break;

                        default:
                            cleanupCategoryOrder($connection2);
                            $cats = array_map(function($elem) {
                                return $elem['value'];
                            },$hpGateway->queryUsedCategoryOrders('ASC')->toArray());

                            //Must make space for the new ordinal, otherwise leave the others be
                            if(array_search($categoryOrder,$cats) != null)
                            {
                                //When the next category ordinal is one more than the new ordinal 
                                $sql = "UPDATE hpCategory SET categoryOrder = categoryOrder + 1 WHERE categoryOrder >= :categoryOrder";
                                try{
                                    $stmnt = $connection2->prepare($sql);
                                    $stmnt->execute(['categoryOrder' => $categoryOrder]);
                                }
                                catch(PDOException $e)
                                {
                                    throw $e;
                                }
                            }
                            break;
                    }
                    $sql = "
                        UPDATE hpCategory
                        SET
                            categoryName = :categoryName,
                            categoryType = :categoryType,
                            categoryOrder = :categoryOrder
                        WHERE
                            categoryID = :categoryID;";
                    $sqlresult = null;
                    try{
                        $stmnt = $connection2->prepare($sql);
                        $sqlresult = $stmnt->execute(array(
                            'categoryID' => $categoryID,
                            'categoryName' => $categoryName,
                            'categoryType' => $categoryType,
                            'categoryOrder' => $categoryOrder
                        ));
                    }
                    catch(PDOException $e)
                    {
                        throw $e;
                    }
                    
                    switch($sqlresult)
                    {
                        case true: $result = 0; break; //fine
                        case false: $result = 1; break; //error, couldn't find category
                        default: $result = 1; break; //error
                    }

                    if($result == 0)
                    {
                        mergeSubCategories($connection2,$categoryID,$subCats);
                    }
                    cleanupCategoryOrder($connection2); //Cleanup again just in case this has caused any weird gaps, particularly with the nudging
                    break;
                default: $result = 4; break;
            }
        }
        else
        {
            $result = 2;
        }
        break;

    case "add":
        cleanupCategoryOrder($connection2);
        if($categoryType != "House" && $categoryType != "Student") $result = 2;
        echo "CatType: " . $categoryType . "<br/>";
        $sql = "
            INSERT INTO hpCategory
            SET
                categoryName = :categoryName,
                categoryType = :categoryType
            ;";
        try
        {
            $stmnt = $connection2->prepare($sql);
            $stmnt->execute([
                "categoryName" => $categoryName,
                "categoryType" => $categoryType
            ]);
            $result = 0;
        }
        catch(PDOException $e)
        {
            throw $e;
            $result = 2;
        }
        if($subCats != '')
        {
            if($result == 0)
            {
                $sql = "SELECT categoryID FROM hpCategory WHERE categoryID = :lastID";
                $stmnt = $connection2->prepare($sql);
                $results = $stmnt->execute([
                    'lastID' => $connection2->lastInsertId()
                ]);
                mergeSubCategories($connection2,$categoryID,$subCats);
            }
        }
        cleanupCategoryOrder($connection2);
        break;

    case "delete":
        try
        {
            $sql = 'DELETE FROM hpSubCategory WHERE categoryID = :categoryID';
            $res = $connection2->prepare($sql)->execute(['categoryID' => $categoryID]);

            $sql = 'DELETE FROM hpCategory WHERE categoryID = :categoryID';
            $res = $connection2->prepare($sql)->execute(['categoryID' => $categoryID]);
        }
        catch(PDOException $e)
        {
            $result = 2;
        }
        if($result != 2) $result = 0;

        cleanupCategoryOrder($connection2);
        break;

    default:
        $result = 3;
        break;
}

switch($result)
{
    case 0: $resultTxt = "Success"; break;
    case 1: $resultTxt = "Error"; break;
    case 2: $resultTxt = "A database error occurred"; break;
    case 3: $resultTxt = "The provided category couldn't be found";break;
    case 4: $resultTxt = "Duplicate categories exist"; break; //Duplicate category IDs, should be covered by the DB PK
    default: $resultTxt = "An unknown error occurred (" . $result . ")"; break;
}
echo "Result " . $result;
//header('Location: ' . $returnTo . '&statusCode=' . $result . '&statusText=' . $resultTxt);
?>