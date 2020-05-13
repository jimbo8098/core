<?php

namespace Gibbon\Domain\CrowdAssessment;

use Gibbon\Domain\QueryableGateway;
use Gibbon\Domain\QueryCriteria;
use Gibbon\Domain\Traits\TableAware;
use Gibbon\Domain\DataSet;

class CrowdAssessmentGateway extends QueryableGateway
{
  use TableAware;
  private static $tableName = '';
  private static $primaryKey = '';
  private static $searchableColumns = [];

  private function getGenericQuery(QueryCriteria $criteria)
  {
    $query = $this
      ->newQuery()
      ->from('gibbonPlannerEntry')
      ->innerJoin('gibbonCourseClass','gibbonPlannerEntry.gibbonCourseClassID = gibbonCourseClass.gibbonCourseClassID'))
      ->innerJoin('gibbonCourseClassPerson','gibbonCourseClass.gibbonCourseClassID = gibbonCourseClassPerson.gibbonCourseClassID')
      ->innerJoin('gibbonCourse','gibbonCourse.gibbonCourseID = gibbonCourseClass.gibbonCourseID')
      ->where('gibbonPlannerEntry.homewordSubmissionDateOpen <= CURRENT_DATE()')
      ->where("gibbonCourseClassPerson.role IN ('Teacher','Student')")
      ->where("gibbonPlannerEntry.gibbonCrowdAssess = 'Y'")
      ->where('ADDTIME(gibbonPlannerEntry.date,INTERVAL 35 DAY) >= CURRENT_TIMESTAMP()')
      ->cols([
        'gibbonPlannerEntry.homeworkDueDateTime',
        'gibbonPlannerEntry.gibbonPlannerEntryID',
        'gibbonPlannerEntry.gibbonUnitID',
        'gibbonCourse.nameShort as gibbonCourseNameShort',
        'gibbonCourseClass.nameShort as gibbonClassNameShort',
        'gibbonPlannerEntry.name as plannerEntryName',
        'gibbomPlannerEntry.start as plannerEntryStart',
        'gibbonPlannerEntry.end as plannerEntryEnd',
        'gibbonPlannerEntry.viewableStudents',
        'gibbonPlannerEntry.viewableParents',
        'gibbonPlannerEntry.homework',
        'gibbonPlannerEntry.homeworkDetails',
        'gibbonPlannerEntry.date',
        'gibbonPlannerEntry.gibbonCourseClassID',
        'gibbonPlannerEntry.homeworkCrowdAssessOtherTeachersRead',
        'gibbonPlannerEntry.homeworkCrowdAssessClassmatesRead',
        'gibbonPlannerEntry.homeworkCrowdAssessOtherStudentsRead',
        'gibbonPlannerEntry.homeworkCrowdAssessSubmitterParentsRead',
        'gibbonPlannerEntry.homeworkCrowdAssessClassmatesParentsRead',
        'gibbonPlannerEntry.homeworkCrowdAssessOtherParentsRead'
     ]);

     $criteria->addFilterRules([
      'gibbonPersonID' => function($query,$personID)
      {
        return $query
          ->where('gibbonCourseClassPerson.gibbonPersonID = :gibbonPerson')
          ->bindValue('gibbonPerson',$personID);
      },
      'gibbonSchoolYearID' => function($query,$schoolYearID)
      {
        return $query
          ->where('gibbonCourseClass.gibbonSchoolYearID = :gibbonSchoolYearID')
          ->bindValue('gibbonSchoolYearID',$schoolYearID);
      }
    ]);
    return $query;
  }

  public function queryCrowdAssessment(QueryCriteria $criteria,$queryType)
  {
    switch($queryType)
    {
      case "staff":
        return $this->runQuery(getCrowdAssessmentStaff($criteria),$criteria),
      default:
        return $this->runQuery(getGenericQuery($criteria),$criteria);
    }
  }


  private function getCrowdAssesmentStaff(QueryCriteria $criteria)
  {
    $query = $this
      ->newQuery()
      ->cols([
        'gibbonPlannerEntry.homeworkDueDateTime',
        'gibbonPlannerEntry.gibbonPlannerEntryID',
        'gibbonPlannerEntry.gibbonUnitID',
        'gibbonCourse.nameShort as gibbonCourseNameShort',
        'gibbonCourseClass.nameShort as gibbonClassNameShort',
        'gibbonPlannerEntry.name as plannerEntryName',
        'gibbomPlannerEntry.start as plannerEntryStart',
        'gibbonPlannerEntry.end as plannerEntryEnd',
        'gibbonPlannerEntry.viewableStudents',
        'gibbonPlannerEntry.viewableParents',
        'gibbonPlannerEntry.homework',
        'gibbonPlannerEntry.homeworkDetails',
        'gibbonPlannerEntry.date',
        'gibbonPlannerEntry.gibbonCourseClassID',
        'gibbonPlannerEntry.homeworkCrowdAssessOtherTeachersRead',
        'gibbonPlannerEntry.homeworkCrowdAssessClassmatesRead',
        'gibbonPlannerEntry.homeworkCrowdAssessOtherStudentsRead',
        'gibbonPlannerEntry.homeworkCrowdAssessSubmitterParentsRead',
        'gibbonPlannerEntry.homeworkCrowdAssessClassmatesParentsRead',
        'gibbonPlannerEntry.homeworkCrowdAssessOtherParentsRead'
      ]);
"UNION (SELECT $fields FROM gibbonPlannerEntry 
      JOIN gibbonCourseClass ON (gibbonPlannerEntry.gibbonCourseClassID=gibbonCourseClass.gibbonCourseClassID) 
      JOIN gibbonCourse ON (gibbonCourse.gibbonCourseID=gibbonCourseClass.gibbonCourseID) 
      WHERE 
        homeworkSubmissionDateOpen<=:today2 
        AND homeworkCrowdAssess='Y' 
        AND ADDTIME(date, '1344:00:00.0')>=:now2 
        AND gibbonSchoolYearID=:gibbonSchoolYearID2 
        AND homeworkCrowdAssessOtherTeachersRead='Y' $and)"

    $query->join('union',
  }

}

?>
