<?php
$sql=array();
$count=0;
$sql[$count][0]="1.00" ; // version number
$sql[$count][1]="" ; // sql statements
$count++;

$sql[$count][0]="1.01" ; // version number
$sql[$count][1]="" ; // sql statements
$count++;

$sql[$count][0]="1.02" ; // version number
$sql[$count][1]="
ALTER TABLE hpCategory ADD categoryType ENUM('House','Student') NOT NULL DEFAULT 'House' AFTER categoryOrder;end
ALTER TABLE hpCategory ADD categoryPresets TEXT NOT NULL AFTER categoryType;end
" ;
$count++;

$sql[$count][0]="1.03" ; // version number
$sql[$count][1]="
UPDATE gibbonAction SET category='Manage' WHERE (name='Manage points' OR name='Categories') AND gibbonModuleID=(SELECT gibbonModuleID FROM gibbonModule WHERE name='House Points');end
UPDATE gibbonAction SET category='Award' WHERE (name='Award student points' OR name='Award house points') AND gibbonModuleID=(SELECT gibbonModuleID FROM gibbonModule WHERE name='House Points');end
UPDATE gibbonAction SET category='View' WHERE (name='View points overall' OR name='View points individual' OR name='View points class' OR name='View my points') AND gibbonModuleID=(SELECT gibbonModuleID FROM gibbonModule WHERE name='House Points');end
" ;
$count++;

$sql[$count][0]="1.3.01" ; // version number
$sql[$count][1]="
ALTER TABLE `hpPointHouse` CHANGE `points` `points` INT(4) UNSIGNED NOT NULL;end
ALTER TABLE `hpPointStudent` CHANGE `points` `points` INT(4) UNSIGNED NOT NULL;end
UPDATE gibbonAction SET precedence=0 WHERE (name='Award student points' OR name='Award house points') AND gibbonModuleID=(SELECT gibbonModuleID FROM gibbonModule WHERE name='House Points');end
INSERT INTO `gibbonAction` (`gibbonModuleID`, `name`, `precedence`, `category`, `description`, `URLList`, `entryURL`, `defaultPermissionAdmin`, `defaultPermissionTeacher`, `defaultPermissionStudent`, `defaultPermissionParent`, `defaultPermissionSupport`, `categoryPermissionStaff`, `categoryPermissionStudent`, `categoryPermissionParent`, `categoryPermissionOther`) VALUES ((SELECT gibbonModuleID FROM gibbonModule WHERE name='House Points'), 'Award student points_unlimited', 1, 'Award', 'Award points to students, without a limit.', 'award.php', 'award.php', 'Y', 'N', 'N', 'N', 'N', 'Y', 'N', 'N', 'N');end
INSERT INTO `gibbonPermission` (`permissionID` ,`gibbonRoleID` ,`gibbonActionID`) VALUES (NULL , '1', (SELECT gibbonActionID FROM gibbonAction JOIN gibbonModule ON (gibbonAction.gibbonModuleID=gibbonModule.gibbonModuleID) WHERE gibbonModule.name='House Points' AND gibbonAction.name='Award student points_unlimited'));end
INSERT INTO `gibbonAction` (`gibbonModuleID`, `name`, `precedence`, `category`, `description`, `URLList`, `entryURL`, `defaultPermissionAdmin`, `defaultPermissionTeacher`, `defaultPermissionStudent`, `defaultPermissionParent`, `defaultPermissionSupport`, `categoryPermissionStaff`, `categoryPermissionStudent`, `categoryPermissionParent`, `categoryPermissionOther`) VALUES ((SELECT gibbonModuleID FROM gibbonModule WHERE name='House Points'), 'Award house points_unlimited', 1, 'Award', 'Award points to house, without a limit.', 'house.php', 'house.php', 'Y', 'N', 'N', 'N', 'N', 'Y', 'N', 'N', 'N');end
INSERT INTO `gibbonPermission` (`permissionID` ,`gibbonRoleID` ,`gibbonActionID`) VALUES (NULL , '1', (SELECT gibbonActionID FROM gibbonAction JOIN gibbonModule ON (gibbonAction.gibbonModuleID=gibbonModule.gibbonModuleID) WHERE gibbonModule.name='House Points' AND gibbonAction.name='Award house points_unlimited'));end
UPDATE `gibbonModule` SET `entryURL`='overall.php' WHERE name='House Points';end
INSERT INTO `hpCategory` (`categoryID`, `categoryName`, `categoryOrder`, `categoryType`, `categoryPresets`) VALUES ('0', '-- Unlimited House Points --', '0', 'House', '');end
INSERT INTO `hpCategory` (`categoryID`, `categoryName`, `categoryOrder`, `categoryType`, `categoryPresets`) VALUES ('0', '-- Unlimited Student Points --', '0', 'Student', '');end
" ;
$count++;


