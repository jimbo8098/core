-- Username: jspeir
-- Password: up7g5mmi

SELECT gibbonPlannerEntryID
,gibbonUnitID
,gibbonCourse.nameShort AS course
,gibbonCourseClass.nameShort AS class
,gibbonPlannerEntry.name
,timeStart
,timeEnd
,viewableStudents
,viewableParents
,homework
,homeworkDetails
,date
,gibbonPlannerEntry.gibbonCourseClassID
,homeworkCrowdAssessOtherTeachersRead
,homeworkCrowdAssessClassmatesRead
,homeworkCrowdAssessOtherStudentsRead
,homeworkCrowdAssessSubmitterParentsRead
,homeworkCrowdAssessClassmatesParentsRead
,homeworkCrowdAssessOtherParentsRead
FROM gibbonPlannerEntry
JOIN gibbonCourseClass ON (gibbonPlannerEntry.gibbonCourseClassID=gibbonCourseClass.gibbonCourseClassID)
JOIN gibbonCourse ON (gibbonCourse.gibbonCourseID=gibbonCourseClass.gibbonCourseID) WHERE homeworkSubmissionDateOpen<='2020-06-01'
AND homeworkCrowdAssess='Y'
AND ADDTIME(date,'1344:00:00.0')>='2020-06-01 22:36:50'
AND gibbonSchoolYearID='025'
-- AND homeworkCrowdAssessOtherParentsRead='Y'
