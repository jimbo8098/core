<?php

namespace Gibbon\Domain\CrowdAssessment;

use Gibbon\Domain\QueryableGateway;
use Gibbon\Domain\QueryCriteria;
use Gibbon\Domain\Traits\TableAware;
use Gibbon\Domain\DataSet;
use Gibbon\Domain\CrowdAssessment\CrowdAssessmentSupplementalGateway;

class CrowdAssessmentGateway extends QueryableGateway
{
  use TableAware;
  private static $tableName = 'gibbonPlannerEntry';
  private static $primaryKey = '';
  private static $searchableColumns = [];
  private $defaultCols = [
    'gibbonPlannerEntry.homeworkDueDateTime',
    'gibbonPlannerEntry.gibbonPlannerEntryID',
    'gibbonPlannerEntry.gibbonUnitID',
    'gibbonCourse.nameShort as gibbonCourseNameShort',
    'gibbonCourseClass.nameShort as gibbonClassNameShort',
    'gibbonPlannerEntry.name as plannerEntryName',
    'gibbonPlannerEntry.timeStart as plannerEntryStart',
    'gibbonPlannerEntry.timeEnd as plannerEntryEnd',
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
  ];

  public function queryCrowdAssessedLessons(QueryCriteria $criteria, $types)
  {

    //Always show crowd assessment entries for the gibbonPersonID the visitor signed in with
    $query = $this
      ->newQuery()
      ->from('gibbonPlannerEntry')
      ->innerJoin('gibbonCourseClass','gibbonPlannerEntry.gibbonCourseClassID = gibbonCourseClass.gibbonCourseClassID')
      ->innerJoin('gibbonCourseClassPerson','gibbonCourseClass.gibbonCourseClassID = gibbonCourseClassPerson.gibbonCourseClassID')
      ->innerJoin('gibbonCourse','gibbonCourse.gibbonCourseID = gibbonCourseClass.gibbonCourseID')
      ->where('gibbonPlannerEntry.homeworkSubmissionDateOpen <= CURRENT_DATE()')
      ->where("gibbonCourseClassPerson.role IN ('Teacher','Student')")
      ->where("gibbonPlannerEntry.homeworkCrowdAssess = 'Y'")
      ->where('DATE_ADD(gibbonPlannerEntry.date,INTERVAL 35 DAY) >= CURRENT_TIMESTAMP()')
      ->where('gibbonCourse.gibbonSchoolYearID = :gibbonSchoolYearID')
      ->where('gibbonCourseClassPerson.gibbonPersonID = :gibbonPersonID')
      ->cols($this->defaultCols);

    $criteria->addFilterRules([
      'gibbonSchoolYearID' => function ($query,$gibbonSchoolYearID)
      {
        return $query
          ->bindValue('gibbonSchoolYearID',$gibbonSchoolYearID);
      },
      'gibbonPersonID' => function ($query,$gibbonPersonID)
      {
        return $query
          ->bindValue('gibbonPersonID',$gibbonPersonID);
      },
      'gibbonFamilyID' => function ($query,$gibbonFamilyID) 
      {
        return $query
          ->bindValue('gibbonFamilyID', $gibbonFamilyID);
      }
    ]);
    

    foreach($types as $type)
    {
      switch($type)
      {
        case "staff":
          $this->unionWithCriteria($query,$criteria)
            ->from('gibbonPlannerEntry')
            ->innerJoin('gibbonCourseClass','gibbonPlannerEntry.gibbonCourseClassID = gibbonCourseClass.gibbonCourseClassID')
            ->innerJoin('gibbonCourse','gibbonCourse.gibbonCourseID = gibbonCourseClass.gibbonCourseClassID')
            ->where('gibbonPlannerEntry.homeworkSubmissionDateOpen <= CURRENT_DATE()')
            ->where("gibbonPlannerEntry.homeworkCrowdAssess = 'Y'")
            ->where('DATE_ADD(gibbonPlannerEntry.date,INTERVAL 35 DAY) >= CURRENT_TIMESTAMP()')
            ->where("gibbonPlannerEntry.homeworkCrowdAssessOtherTeachersRead = 'Y'")
            ->where('gibbonCourse.gibbonSchoolYearID = :gibbonSchoolYearID')
            ->cols($this->defaultCols);
          break;

        case "student":
          //Get the lessons for the provided student ID
          $this->unionWithCriteria($query,$criteria)
            ->from('gibbonPlannerEntry')
            ->innerJoin('gibbonCourseClass','gibbonCourseClass.gibbonCourseClassID = gibbonPlannerEntry.gibbonCourseClassID')
            ->innerJoin('gibbonCourse', 'gibbonCourse.gibbonCourseID = gibbonCourseClass.gibbonCourseClassID')
            ->where('gibbonPlannerEntry.homeworkSubmissionDateOpen <= CURRENT_DATE()')
            ->where("gibbonPlannerEntry.homeworkCrowdAssess = 'Y'")
            ->where('DATE_ADD(gibbonPlannerEntry.date,INTERVAL 35 DAY) >= CURRENT_TIMESTAMP()')
            ->where("gibbonPlannerEntry.homeworkCrowdAssessOtherStudentsRead = 'Y'")
            ->where('gibbonCourse.gibbonSchoolYearID = :gibbonSchoolYearID')
            ->cols($this->defaultCols);
          break;

        case "parent":
          $this->unionWithCriteria($query,$criteria)
            ->from('gibbonPlannerEntry')
            ->innerJoin('gibbonCourseClass','gibbonCourseClass.gibbonCourseClassID = gibbonPlannerEntry.gibbonCourseClassID')
            ->innerJoin('gibbonCourseClassPerson','gibbonCourseClass.gibbonCourseClassID = gibbonCourseClassPerson.gibbonCourseClassID')
            ->innerJoin('gibbonCourse', 'gibbonCourse.gibbonCourseID = gibbonCourseClass.gibbonCourseClassID')
            ->where('gibbonPlannerEntry.homeworkSubmissionDateOpen <= CURRENT_DATE()')
            ->where('gibbonCourseClassPerson.gibbonPersonID = :gibbonPersonID')
            ->where("gibbonPlannerEntry.homeworkCrowdAssess = 'Y'")
            ->where('DATE_ADD(gibbonPlannerEntry.date,INTERVAL 35 DAY) >= CURRENT_TIMESTAMP()')
            ->where("gibbonCourseClassPerson.role = 'Student'")
            ->where("(gibbonPlannerEntry.homeworkCrowdAssessSubmitterParentsRead = 'Y' OR
              gibbonPlannerEntry.homeworkCrowdAssessClassmatesParentsRead = 'Y')")
            ->where('gibbonCourse.gibbonSchoolYearID = :gibbonSchoolYearID')
            ->cols($this->defaultCols);
          break;

        case "other":
          //Get lessons which are available for assessment by any parent
          $this->unionWithCriteria($query,$criteria)
            ->from('gibbonPlannerEntry')
            ->innerJoin('gibbonCourseClass','gibbonCourseClass.gibbonCourseClassID = gibbonPlannerEntry.gibbonCourseClassID')
            ->innerJoin('gibbonCourse', 'gibbonCourse.gibbonCourseID = gibbonCourseClass.gibbonCourseClassID')
            ->where('gibbonPlannerEntry.homeworkSubmissionDateOpen <= CURRENT_DATE()')
            ->where("gibbonPlannerEntry.homeworkCrowdAssess = 'Y'")
            ->where('DATE_ADD(gibbonPlannerEntry.date,INTERVAL 35 DAY) >= CURRENT_TIMESTAMP()')
            ->where("gibbonPlannerEntry.homeworkCrowdAssessOtherParentsRead = 'Y'")
            ->where('gibbonCourse.gibbonSchoolYearID = :gibbonSchoolYearID')
            ->cols($this->defaultCols);
          break;
      }
    }
    return $this->runQuery($query,$criteria);
  }

  private function defaultCriteria (QueryCriteria $criteria)
  {
    
  }

}

?>
