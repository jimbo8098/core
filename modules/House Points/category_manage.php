<?php

use Gibbon\Module\HousePoints\Domain\HousePointsGateway;
use Gibbon\Tables\DataTable;
use Gibbon\Services\Format;
use Gibbon\Forms\Form;
use Gibbon\Forms\Prefab\DeleteForm;

//include '../gibbon.php';

$mode = $_GET['mode'] ?? '';
$categoryID = $_GET['categoryID'] ?? '';
$returnTo = $_GET['returnTo'] ?? '';
$absoluteURL = $_SESSION[$guid]['absoluteURL'];

// manage house point categories
if (isActionAccessible($guid, $connection2,"/modules/House Points/category_manage.php")==FALSE) {
    //Acess denied
    print "<div class='error'>" ;
        print "You do not have access to this action." ;
    print "</div>" ;
} else {

    //Common
    $page->breadcrumbs->add(__('Categories'));
    $hpGateway = $container->get(HousePointsGateway::Class);

    switch($mode)
    {
        case 'add':
        case 'edit':
            echo showAddEdit($hpGateway,$categoryID,$mode,$absoluteURL);
            break;

        case 'delete':
            echo showDelete($categoryID,$absoluteURL);
            break;

        case 'deleteconfirmed':
            deleteCategory($categoryID);
            break;

        default:
            echo showViewAll($hpGateway,$absoluteURL);
            break;
    }
}

function deleteCategory($categoryID)
{
    try
    {
        $sql = "DELETE FROM hpCategories WHERE categoryID = :categoryID";
        $stmnt = $connection2->prepare($sql);
        $stmnt->execute(array(
            'categoryID' => $categoryID
        ));
        $returnTo .= '&return=error2';
        header("Location: {$returnTo}");
        exit();
    }
    catch(PDOException $e)
    {
        $returnTo .= '&return=error2';
        header("Location: {$returnTo}");
        exit();
    }
}

function showDelete(HousePointsGateway $hpGateway,$absoluteURL)
{
    $catFunctions = $absoluteURL.'index.php?q=/modules/House Points/category_functions.php';
    $form = DeleteForm::createForm($catFunctions . '7mode=deleteconfirmed&categoryID='.$categoryID."&returnTo=" . $absoluteURL.'/modules/'.$_SESSION[$guid]['module']. '/category_manage.php');
    return $form->getOutput();
}

function showViewAll(HousePointsGateway $hpGateway,$absoluteURL)
{
    $catFunctions = $absoluteURL.'/modules/'.$_SESSION[$guid]['module']. '/category_functions.php';
    $criteria = $hpGateway->newQueryCriteria()
        ->sortBy('categoryOrder','DESC');
    $categories = $hpGateway->queryCategories($criteria,false);
    $table = DataTable::create('categories');
    $table->addHeaderAction('add',__('Add'))
        ->addParam('mode','add')
        ->addParam('q','/modules/House Points/category_manage.php');
    $table->addColumn('categoryName',__('Category'));
    $actions = $table->addActionColumn('actions',__('Actions'));
        $actions->format(function($row,$actions) {

            $actions->addAction('edit',__('Edit'))
                ->setURL($catFunctions)
                ->addParam('categoryID',$row['categoryID'])
                ->addParam('mode','edit');

            $actions->addAction('delete',__('Delete'))
                ->setURL($catFunctions)
                ->addParam('categoryID',$row['categoryID'])
                ->addParam('mode','delete');
        });
    
    return $table->render($categories);
}

function showAddEdit(HousePointsGateway $hpGateway, $categoryID, $mode,$absoluteURL)
{
    $form = Form::create('catform', $absoluteURL.'/index.php','POST');
    $form->addHiddenValue('q','/modules/House Points/category_function.php');
    $form->addHiddenValue('categoryID', $categoryID ?? 0);
    $form->addHiddenValue('returnTo',$absoluteURL . '?q=/modules/House Points/category_manage.php');
    $form->addHiddenValue('mode', 'add');    

    $row = $form->addRow();
        $row->addLabel('categoryName', __('Category Name'));
        $row->addTextField('categoryName')->required()->maxLength(45)->setValue($categoryName ?? '');

    $row = $form->addRow();
        $row->addLabel('categoryType', __('Type'));
        $row->addSelect('categoryType')->fromArray(array('House', 'Student'))->selected($categoryType ?? '');

    $row = $form->addRow();
        $row->addLabel('categoryPresets', __('Presets'))
            ->description(__('Add preset comma-separated increments as Name: PointValue. Leave blank for unlimited.'))
            ->description(__(' eg: ThingOne: 1, ThingTwo: 5, ThingThree: 10'));
        $row->addTextArea('categoryPresets')->setRows(2)->setValue($categoryPresets ?? '');

    $row = $form->addRow();
        $row->addSubmit(__('Save'));

    return $form->getOutput();
}
