<?php
use Gibbon\Forms\Form;

$mode = $_POST['mode'] ?? $_GET['mode'] ?? '';
$categoryID = isset($_POST['categoryID'])? $_POST['categoryID'] : '';
$categoryName = isset($_POST['categoryName'])? trim($_POST['categoryName']) : '';
$categoryType = isset($_POST['categoryType'])? $_POST['categoryType'] : '';
$categoryPresets = isset($_POST['categoryPresets'])? trim($_POST['categoryPresets']) : '';
$returnTo = $_POST['returnTo'] ?? $_GET['returnTo'] ?? '';
