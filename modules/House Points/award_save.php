<?php
include  "../../gibbon.php";

$dbh = $connection2;
$highestAction = getHighestGroupedAction($guid, '/modules/House Points/award.php', $dbh);

$returnTo = $_GET['return'];
$mode = $_GET['mode'];
$houseID = $_POST['houseID'];
$teacherID = $_GET['teacherID'];
$studentID = $_GET['studentID'];
$categoryID = $_GET['categoryID'];
$points = $_GET['points'];
$reason = $_GET['reason'];
$yearID = $_SESSION[$guid]['gibbonSchoolYearID'];

switch($mode)
{
    case "house": if($houseID == 0) return "Please select a house";
    case "student": if($studentID == 0) return "Please select a student;";
    default: return "Please select an award mode";
}

if ($categoryID == 0) return "Please select a category";
if (empty($reason)) return "Please provide a detailed reason";
if ($highestAction != 'Award student points_unlimited') {
    if ($points<1 || $points>20) {
        $msg .= "Please award between 1 and 20 points<br />"; 
    }
}

$data = array(
    'houseID' => $houseID,
    'categoryID' => $categoryID,
    'points' => $points,
    'reason' => $reason,
    'yearID' => $_SESSION[$guid]['gibbonSchoolYearID'],
    'awardedDate' => date('Y-m-d'),
    'awardedBy' => $teacherID,
    'studentID' => $studentID
);

$sql = "";
switch($mode)
{
    case "student":
        $sql = "INSERT INTO hpPointStudent
            SET studentID = :studentID,
            categoryID = :categoryID,
            points = :points,
            reason = :reason,
            yearID = :yearID,
            awardedDate = :awardedDate,
            awardedBy = :awardedBy";
        break;

    case "house":
        $sql = "INSERT INTO hpPointHouse(
            houseID,
            categoryID,
            points,
            reason,
            yearID,
            awardedDate,
            awardedBy
        )
        VALUES (
            :houseID,
            :categoryID
            :points,
            :reason,
            :yearID,
            :awardedDate,
            :awardedBy
        );";
        break;
}

$rs = $dbh->prepare($sql);
$ok = $rs->execute($data);
if ($ok) {
    $msg = "Points successfully added";
} else {
    $msg = "Problem - contact system adminstrator";
}


if($returnTo != null)
{
    header("Location: " .$gibbon->session->get('absoluteURL') . "?q=". $returnTo) . "&result=0";
}
else
{
    echo $msg;
}