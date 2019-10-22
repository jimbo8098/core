<?php
use Gibbon\Forms\Form;
use Gibbon\Forms\DatabaseFormFactory;
use Gibbon\Module\HousePoints\Domain\HousePointsGateway;
use Gibbon\Tables\DataTable;
use Gibbon\Services\Format;

include __DIR__ . '/moduleFunctions.php';
// manage house point categories
if (isActionAccessible($guid, $connection2,"/modules/House Points/house_award.php")==FALSE) {
    //Acess denied
    print "<div class='error'>" ;
            print "You do not have access to this action." ;
    print "</div>" ;
} else {
    
    $page->breadcrumbs->add(__('Award house points'));

    if(isset($_GET['result']))
    {
        showResultAlert($_GET['result']);
    }
    global $pdo;
        
    echo "<p>&nbsp;</p>";
    echo "<h3>Award house points to house</h3>";

    $form = Form::create('awardForm', $gibbon->session->get('absoluteURL') . '/index.php','get');
    $form->addHiddenValue('q','/modules/House Points/award_save.php');
    $form->addHiddenValue('return',$gibbon->session->get('address'));
    $form->addHiddenValue('mode','house');
    $form->addHiddenValue('teacherID', $gibbon->session->get('gibbonPersonID') ?? '');

    $sql = "SELECT gibbonHouse.gibbonHouseID AS value, gibbonHouse.name FROM gibbonHouse ORDER BY gibbonHouse.name";
    $row = $form->addRow();
        $row->addLabel('houseID', __('House'));
        $row->addSelect('houseID')->fromQuery($pdo, $sql)->required()->placeholder()->selected($_GET['houseID'] ?? '');

    $highestAction = getHighestGroupedAction($guid, '/modules/House Points/house_award.php', $connection2);
    $unlimitedPoints = ($highestAction == 'Award house points_unlimited');

    $hpGateway = $container->get(HousePointsGateway::Class);
    $criteria = $hpGateway->newQueryCriteria()->filterBy('categoryType','House');
    $categories = $hpGateway->queryCategories($criteria,true,false);

    $row = $form->addRow();
        $row->addLabel('categoryID', __('Category'));
        $row->addSelect('categoryID')
            ->fromDataSet($categories,'value','name')
            ->required()
            ->placeholder();

    $row = $form->addRow();
        $row->addLabel('points', __('Points'));
        $row->addNumber('points')
        ->placeholder(__('Points to add'));

    $row = $form->addRow();
        $row->addLabel('reason', __('Reason'));
        $row->addTextArea('reason')->setRows(2)->required();
        
    $row = $form->addRow();
        $row->addSubmit('Submit','submit');

    echo $form->getOutput();        
}
