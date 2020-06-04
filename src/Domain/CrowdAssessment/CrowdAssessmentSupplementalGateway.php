<?php

namespace Gibbon\Domain\CrowdAssessment;

use Gibbon\Domain\QueryableGateway;
use Gibbon\Domain\QueryCriteria;
use Gibbon\Domain\Traits\TableAware;
use Gibbon\Domain\DataSet;

class CrowdAssessmentSupplementalGateway extends QueryableGateway
{
  use TableAware;
  private static $tableName = '';
  private static $primaryKey = '';
  private static $searchableColumns = [];
  private $defaultCols = [
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
  ];

  private $defaultCriteria = [
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
  ];

  private function defaultQuery()
  {
    $query = $this
      ->newQuery()
      ->from('gibbonPlannerEntry')
      ->innerJoin('gibbonCourseClass','gibbonPlannerEntry.gibbonCourseClassID = gibbonCourseClass.gibbonCourseClassID')
      ->innerJoin('gibbonCourse','gibbonCourse.gibbonCourseID = gibbonCourseClass.gibbonCourseClassID')
      ->where('gibbonPlannerEntry.homeworkSubmissionDateOpen <= CURRENT_DATE()')
      ->where("gibbonPlannerEntry.gibbonCrowdAssess = 'Y'")
      ->where('ADDTIME(gibbonPlannerEntry.date,INTERVAL 35 DAY) >= CURRENT_TIMESTAMP()')
      ->cols($this->defaultCols);
  }


  public function queryStaff(QueryCriteria $criteria)
  {
    return $this
      ->defaultQuery()
      ->where("gibbonPlannerEntry.homeworkCrowdAssessOtherTeachersRead = 'Y'");
  }

  public function queryStudent(QueryCriteria $criteria)
  {
    $query = $this
      ->defaultQuery()
      ->where("gibbonPlannerEntry.homeworkCrowdAssessOtherStudentsRead = 'Y'");

    $criteria->addFilterRules($defaultCriteria);
    return [
      'query' => $query,
      'criteria' => $criteria
    ];
  }

  public function queryParent(QueryCriteria $criteria)
  {
    $query = $this
      ->defaultQuery()
      ->innerJoin('gibbonCourseClassPerson','gibbonCourseClass.gibbonCourseClassID = gibbonCourseClassPerson.gibbonCourseClassID')
      ->innerJoin('gibbonPerson', 'gibbonCourseClassPerson.gibbonPersonID = gibbonPerson.gibbonPersonID')

      ->where("gibbonCourseClassPerson.role = 'Student'")
      ->where("(gibbonPlannerEntry.homeworkCrowdAssessSubmitterParentsRead = 'Y' OR
        gibbonPlannerEntry.homeworkCrowdAssessClassmatesParentsRead = 'Y')")
      ->where('(gibbonPerson.dateStart IS NULL OR gibbonPerson.dateStart <= CURRENT_TIMESTAMP())')
      ->where('(gibbonPerson.dateEnd IS NULL OR gibbonPerson.dateEnd >= CURRENT_TIMESTAMP())');

    array_push($defaultCriteria,function($query,$familyID)
    {
      return $query
        ->innerJoin('gibbonPerson', 'gibbonCourseClassPerson.gibbonPersonID = gibbonPerson.gibbonPersonID')
        ->innerJoin('gibbonFamilyChild','gibbonPerson.gibbonPersonID = gibbonFamilyChild.gibbonPersonID')
        ->where('(gibbonPerson.dateStart IS NULL OR gibbonPerson.dateStart <= CURRENT_TIMESTAMP())')
        ->where('(gibbonPerson.dateEnd IS NULL OR gibbonPerson.dateEnd >= CURRENT_TIMESTAMP())')
        ->where("gibbonPerson.status = 'Full'")
        ->where('gibbonFamilyChild.gibbonFamilyID = :gibbonFamilyID')
        ->bindValue('gibbonFamilyID',$familyID);
    });

    $criteria->addFilterRules($defaultCriteria);
    return [
      'query' => $query,
      'criteria' => $criteria
    ];
  }

  public function queryOther(QueryCriteria $criteria)
  {
    $query = $this
      ->defaultQuery()
      ->where("gibbonPlannerEntry.homeworkCrowdAssessOtherParentsRead = 'Y'");
    $criteria->addFilterRules($defaultCriteria);
    return [
      'query' => $query,
      'criteria' => $criteria
    ];
  }
}

?>
