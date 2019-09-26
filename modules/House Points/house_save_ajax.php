<?php
include  "../../gibbon.php";

$dbh = $connection2;
$highestAction = getHighestGroupedAction($guid, '/modules/House Points/house.php', $dbh);

$formData = array();
parse_str($_POST['formData'], $formData);

$teacherID = $formData['teacherID'];
$houseID = $formData['houseID'];
$categoryID = $formData['categoryID'];
$points = $formData['points'];
$reason = $formData['reason'];
$yearID = $_SESSION[$guid]['gibbonSchoolYearID'];

$msg = '';
if ($houseID == 0) {
    $msg .= "Please select a house<br />"; 
}
if ($categoryID == 0) {
    $msg .= "Please select a category<br />"; 
}
if (empty($reason)) {
    $msg .= "Please provide a detailed reason<br />"; 
}
if ($highestAction != 'Award house points_unlimited') {
    if ($points<1 || $points>200) {
        $msg .= "Please award between 1 and 200 points<br />"; 
    }
}

if ($msg == '') {
    $data = array(
        'houseID' => $houseID,
        'categoryID' => $categoryID,
        'points' => $points,
        'reason' => $reason,
        'yearID' => $yearID,
        'awardedDate' => date('Y-m-d'),
        'awardedBy' => $teacherID
    );
    $sql = "INSERT INTO hpPointHouse
        SET houseID = :houseID,
        categoryID = :categoryID,
        points = :points,
        reason = :reason,
        yearID = :yearID,
        awardedDate = :awardedDate,
        awardedBy = :awardedBy";
    $rs = $dbh->prepare($sql);
    $ok = $rs->execute($data);
    if ($ok) {
        $msg = "Points successfully added";
    } else {
        $msg = "Problem - contact system adminstrator";
    }
}

echo $msg;