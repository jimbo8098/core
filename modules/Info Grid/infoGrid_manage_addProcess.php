<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

include '../../gibbon.php';

include './moduleFunctions.php';



$URL = $_SESSION[$guid]['absoluteURL'].'/index.php?q=/modules/'.getModuleName($_POST['address']).'/infoGrid_manage_add.php&search='.$_GET['search'];

if (isActionAccessible($guid, $connection2, '/modules/Info Grid/infoGrid_manage_add.php') == false) {
    //Fail 0
    $URL = $URL.'&return=error0';
    header("Location: {$URL}");
} else {
    //Proceed!
    $title = $_POST['title'];
    $staff = $_POST['staff'];
    $student = $_POST['student'];
    $parent = $_POST['parent'];
    $priority = $_POST['priority'];
    $url = $_POST['url'];
    $logoLicense = $_POST['logoLicense'];

    if ($title == '' or $staff == '' or $student == '' or $parent == '' or $priority == '' or $url == '') {
        //Fail 3
        $URL = $URL.'&return=error3';
        header("Location: {$URL}");
    } else {
        $partialFail = false;
        $logo = null;

        //Move attached image  file, if there is one
        if (!empty($_FILES['file']['tmp_name'])) {
            $fileUploader = new Gibbon\FileUploader($pdo, $gibbon->session);
            $fileUploader->getFileExtensions('Graphics/Design');

            $file = (isset($_FILES['file']))? $_FILES['file'] : null;

            // Upload the file, return the /uploads relative path
            $logo = $fileUploader->uploadFromPost($file, 'infoGrid');

            if (empty($logo)) {
                $partialFail = true;
            }
        }

        //Write to database
        try {
            $data = array('title' => $title, 'staff'=>$staff, 'student'=>$student, 'parent'=>$parent, 'priority' => $priority, 'url' => $url, 'logo' => $logo, 'logoLicense' => $logoLicense, 'gibbonPersonIDCreator' => $_SESSION[$guid]['gibbonPersonID'], 'timestampCreated' => date('Y-m-d H:i:s'));
            $sql = 'INSERT INTO infoGridEntry SET title=:title, staff=:staff, student=:student, parent=:parent, priority=:priority, url=:url, logo=:logo, logoLicense=:logoLicense, gibbonPersonIDCreator=:gibbonPersonIDCreator, timestampCreated=:timestampCreated';
            $result = $connection2->prepare($sql);
            $result->execute($data);
        } catch (PDOException $e) {
            //Fail 2
            $URL = $URL.'&return=error2';
            header("Location: {$URL}");
            exit();
        }

        $AI = str_pad($connection2->lastInsertID(), 8, '0', STR_PAD_LEFT);

        if ($partialFail == true) {
            $URL .= '&return=warning1';
            header("Location: {$URL}");
        } else {
            $URL .= "&return=success0&editID=$AI";
            header("Location: {$URL}");
        }
    }
}
