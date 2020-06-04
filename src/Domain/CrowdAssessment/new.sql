SELECT SQL_CALC_FOUND_ROWS
    `gibbonPlannerEntry`.`homeworkDueDateTime`,
    `gibbonPlannerEntry`.`gibbonPlannerEntryID`,
    `gibbonPlannerEntry`.`gibbonUnitID`,
    `gibbonCourse`.`nameShort` AS `gibbonCourseNameShort`,
    `gibbonCourseClass`.`nameShort` AS `gibbonClassNameShort`,
    `gibbonPlannerEntry`.`name` AS `plannerEntryName`,
    `gibbonPlannerEntry`.`timeStart` AS `plannerEntryStart`,
    `gibbonPlannerEntry`.`timeEnd` AS `plannerEntryEnd`,
    `gibbonPlannerEntry`.`viewableStudents`,
    `gibbonPlannerEntry`.`viewableParents`,
    `gibbonPlannerEntry`.`homework`,
    `gibbonPlannerEntry`.`homeworkDetails`,
    `gibbonPlannerEntry`.`date`,
    `gibbonPlannerEntry`.`gibbonCourseClassID`,
    `gibbonPlannerEntry`.`homeworkCrowdAssessOtherTeachersRead`,
    `gibbonPlannerEntry`.`homeworkCrowdAssessClassmatesRead`,
    `gibbonPlannerEntry`.`homeworkCrowdAssessOtherStudentsRead`,
    `gibbonPlannerEntry`.`homeworkCrowdAssessSubmitterParentsRead`,
    `gibbonPlannerEntry`.`homeworkCrowdAssessClassmatesParentsRead`,
    `gibbonPlannerEntry`.`homeworkCrowdAssessOtherParentsRead`
FROM
    `gibbonPlannerEntry`
INNER JOIN `gibbonCourseClass` ON `gibbonPlannerEntry`.`gibbonCourseClassID` = `gibbonCourseClass`.`gibbonCourseClassID`
INNER JOIN `gibbonCourseClassPerson` ON `gibbonCourseClass`.`gibbonCourseClassID` = `gibbonCourseClassPerson`.`gibbonCourseClassID`
INNER JOIN `gibbonCourse` ON `gibbonCourse`.`gibbonCourseID` = `gibbonCourseClass`.`gibbonCourseID`
WHERE
    `gibbonPlannerEntry`.`homeworkSubmissionDateOpen` <= CURRENT_DATE()
    AND `gibbonCourseClassPerson`.`role` IN ('Teacher','Student')
    AND `gibbonPlannerEntry`.`homeworkCrowdAssess` = 'Y'
    AND DATE_ADD(`gibbonPlannerEntry`.`date`,INTERVAL 35 DAY) >= CURRENT_TIMESTAMP()
UNION
SELECT
    `gibbonPlannerEntry`.`homeworkDueDateTime`,
    `gibbonPlannerEntry`.`gibbonPlannerEntryID`,
    `gibbonPlannerEntry`.`gibbonUnitID`,
    `gibbonCourse`.`nameShort` AS `gibbonCourseNameShort`,
    `gibbonCourseClass`.`nameShort` AS `gibbonClassNameShort`,
    `gibbonPlannerEntry`.`name` AS `plannerEntryName`,
    `gibbonPlannerEntry`.`timeStart` AS `plannerEntryStart`,
    `gibbonPlannerEntry`.`timeEnd` AS `plannerEntryEnd`,
    `gibbonPlannerEntry`.`viewableStudents`,
    `gibbonPlannerEntry`.`viewableParents`,
    `gibbonPlannerEntry`.`homework`,
    `gibbonPlannerEntry`.`homeworkDetails`,
    `gibbonPlannerEntry`.`date`,
    `gibbonPlannerEntry`.`gibbonCourseClassID`,
    `gibbonPlannerEntry`.`homeworkCrowdAssessOtherTeachersRead`,
    `gibbonPlannerEntry`.`homeworkCrowdAssessClassmatesRead`,
    `gibbonPlannerEntry`.`homeworkCrowdAssessOtherStudentsRead`,
    `gibbonPlannerEntry`.`homeworkCrowdAssessSubmitterParentsRead`,
    `gibbonPlannerEntry`.`homeworkCrowdAssessClassmatesParentsRead`,
    `gibbonPlannerEntry`.`homeworkCrowdAssessOtherParentsRead`
FROM
    `gibbonPlannerEntry`
INNER JOIN `gibbonCourseClass` ON `gibbonPlannerEntry`.`gibbonCourseClassID` = `gibbonCourseClass`.`gibbonCourseClassID`
INNER JOIN `gibbonCourse` ON `gibbonCourse`.`gibbonCourseID` = `gibbonCourseClass`.`gibbonCourseClassID`
WHERE
    `gibbonPlannerEntry`.`homeworkSubmissionDateOpen` <= CURRENT_DATE()
    AND `gibbonPlannerEntry`.`homeworkCrowdAssess` = 'Y'
    AND DATE_ADD(`gibbonPlannerEntry`.`date`,INTERVAL 35 DAY) >= CURRENT_TIMESTAMP()
    AND `gibbonPlannerEntry`.`homeworkCrowdAssessOtherTeachersRead` = 'Y'
UNION
SELECT
    `gibbonPlannerEntry`.`homeworkDueDateTime`,
    `gibbonPlannerEntry`.`gibbonPlannerEntryID`,
    `gibbonPlannerEntry`.`gibbonUnitID`,
    `gibbonCourse`.`nameShort` AS `gibbonCourseNameShort`,
    `gibbonCourseClass`.`nameShort` AS `gibbonClassNameShort`,
    `gibbonPlannerEntry`.`name` AS `plannerEntryName`,
    `gibbonPlannerEntry`.`timeStart` AS `plannerEntryStart`,
    `gibbonPlannerEntry`.`timeEnd` AS `plannerEntryEnd`,
    `gibbonPlannerEntry`.`viewableStudents`,
    `gibbonPlannerEntry`.`viewableParents`,
    `gibbonPlannerEntry`.`homework`,
    `gibbonPlannerEntry`.`homeworkDetails`,
    `gibbonPlannerEntry`.`date`,
    `gibbonPlannerEntry`.`gibbonCourseClassID`,
    `gibbonPlannerEntry`.`homeworkCrowdAssessOtherTeachersRead`,
    `gibbonPlannerEntry`.`homeworkCrowdAssessClassmatesRead`,
    `gibbonPlannerEntry`.`homeworkCrowdAssessOtherStudentsRead`,
    `gibbonPlannerEntry`.`homeworkCrowdAssessSubmitterParentsRead`,
    `gibbonPlannerEntry`.`homeworkCrowdAssessClassmatesParentsRead`,
    `gibbonPlannerEntry`.`homeworkCrowdAssessOtherParentsRead`
FROM
    `gibbonPlannerEntry`
INNER JOIN `gibbonCourseClass` ON `gibbonCourseClass`.`gibbonCourseClassID` = `gibbonPlannerEntry`.`gibbonCourseClassID`
INNER JOIN `gibbonCourse` ON `gibbonCourse`.`gibbonCourseID` = `gibbonCourseClass`.`gibbonCourseClassID`
WHERE
    `gibbonPlannerEntry`.`homeworkSubmissionDateOpen` <= CURRENT_DATE()
    AND `gibbonPlannerEntry`.`homeworkCrowdAssess` = 'Y'
    AND DATE_ADD(`gibbonPlannerEntry`.`date`,INTERVAL 35 DAY) >= CURRENT_TIMESTAMP()
    AND `gibbonPlannerEntry`.`homeworkCrowdAssessOtherParentsRead` = 'Y'
LIMIT 50
