<?php

use Gibbon\Module\HousePoints\Domain\HousePointsGateway;
use Gibbon\Tables\DataTable;
use Gibbon\Services\Format;
use Gibbon\Forms\Form;

// manage house point categories
if (isActionAccessible($guid, $connection2,"/modules/House Points/category_manage.php")==FALSE) {
    //Acess denied
    print "<div class='error'>" ;
            print "You do not have access to this action." ;
    print "</div>" ;
} else {

    $page->breadcrumbs->add(__('Categories'));
    
    $hpGateway = $container->get(HousePointsGateway::Class);
    $criteria = $hpGateway->newQueryCriteria()
        ->sortBy('categoryOrder','DESC');
    $categories = $hpGateway->queryCategories($criteria,false);
    $table = DataTable::create('categories');
    $table->addColumn('categoryName',__('Category'));
    $actions = $table->addActionColumn('actions',__('Actions'));
        $actions->format(function($row,$actions) {

            $actions->addAction('edit',__('Edit'))
                ->setURL('/modules/House Points/category.php')
                ->addParam('categoryID',$row['categoryID'])
                ->addParam('mode','edit');

            $actions->addAction('delete',__('Delete'))
                ->setURL('/modules/House Points/category.php')
                ->addParam('categoryID',$row['categoryID'])
                ->addParam('mode','delete');
        });
    
    echo $table->render($categories);

}
