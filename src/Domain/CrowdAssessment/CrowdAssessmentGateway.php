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
  private static $tableName = '';
  private static $primaryKey = '';
  private static $searchableColumns = [];
  private static $defaultCols = [
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
  private $supplementalGateway = new CrowdAssessmentSupplementalGateway();

  private function queryCrowdAssessedLessons(QueryCriteria $criteria, $type)
  {
    $query = $this
      ->newQuery()
      ->from('gibbonPlannerEntry')
      ->innerJoin('gibbonCourseClass','gibbonPlannerEntry.gibbonCourseClassID = gibbonCourseClass.gibbonCourseClassID')
      ->innerJoin('gibbonCourseClassPerson','gibbonCourseClass.gibbonCourseClassID = gibbonCourseClassPerson.gibbonCourseClassID')
      ->innerJoin('gibbonCourse','gibbonCourse.gibbonCourseID = gibbonCourseClass.gibbonCourseID')
      ->where('gibbonPlannerEntry.homewordSubmissionDateOpen <= CURRENT_DATE()')
      ->where("gibbonCourseClassPerson.role IN ('Teacher','Student')")
      ->where("gibbonPlannerEntry.gibbonCrowdAssess = 'Y'")
      ->where('ADDTIME(gibbonPlannerEntry.date,INTERVAL 35 DAY) >= CURRENT_TIMESTAMP()')
      ->cols($defaultCols);

    switch($type)
    {
      case "staff":
        $query
          ->union()
          ->from('gibbonPlannerEntry')
          ->innerJoin('gibbonCourseClass','gibbonPlannerEntry.gibbonCourseClassID = gibbonCourseClass.gibbonCourseClassID')
          ->innerJoin('gibbonCourse','gibbonCourse.gibbonCourseID = gibbonCourseClass.gibbonCourseID')
          ->where('gibbonPlannerEntry.homeworkSubmissionDateOpen <= CURRENT_DATE()')
          ->where("gibbonPlannerEntry.gibbonCrowdAssess = 'Y'")
          ->where('ADDTIME(gibbonPlannerEntry.date,INTERVAL 35 DAY) >= CURRENT_TIMESTAMP()')
          ->where("gibbonPlannerEntry.homewordCrowdAssessOtherTeachersRead = 'Y'")
          ->cols($defaultCols);
        break;

      case "student":
        $query
          ->union()
          ->from('gibbonPlannerEntry')
          ->innerJoin('gibbonCourseClass','gibbonCourseClass.gibbonCourseClassID = gibbonPlannerEntry.gibbonCourseClassID')
          ->innerJoin('gibbonCourse', 'gibbonCourse.gibbonCourseID = gibbonCourseClass.gibbonCourseClassID')
          ->where('gibbonPlannerEntry.homeworkSubmissionDateOpen <= CURRENT_DATE()')
          ->where("gibbonPlannerEntry.gibbonCrowdAssess = 'Y'")
          ->where('ADDTIME(gibbonPlannerEntry.date,INTERVAL 35 DAY) >= CURRENT_TIMESTAMP()')
          ->where("gibbonPlannerEntry.homeworkCrowdAssessOtherStudentsRead = 'Y'")
          ->cols($defaultCols);
        break;

      case "parent":
        //Get gibbonPersonIDs of students the parent is responsible for
        $query
          ->union()
          ->from('gibbonPlannerEntry')
          ->innerJoin('gibbonCourseClass','gibbonCourseClass.gibbonCourseClassID = gibbonPlannerEntry.gibbonCourseClassID')
          ->innerJoin('gibbonCourseClassPerson','gibbonCourseClass.gibbonCourseClassID = gibbonCourseClassPerson.gibbonCourseClassID')
          ->innerJoin('gibbonCourse', 'gibbonCourse.gibbonCourseID = gibbonCourseClass.gibbonCourseClassID')
          ->where('gibbonPlannerEntry.homeworkSubmissionDateOpen <= CURRENT_DATE()')
          ->where("gibbonPlannerEntry.gibbonCrowdAssess = 'Y'")
          ->where('ADDTIME(gibbonPlannerEntry.date,INTERVAL 35 DAY) >= CURRENT_TIMESTAMP()')
          ->where("gibbonCourseClassPerson.role = 'Student'")
          ->where("(gibbonPlannerEntry.homeworkCrowdAssessSubmitterParentsRead = 'Y' OR
            gibbonPlannerEntry.homeworkCrowdAssessClassmatesParentsRead = 'Y')")
            ->cols($defaultCols);
        break;

      case "other":
        $query
          ->union()
          ->from('gibbonPlannerEntry')
          ->innerJoin('gibbonCourseClass','gibbonCourseClass.gibbonCourseClassID = gibbonPlannerEntry.gibbonCourseClassID')
          ->innerJoin('gibbonCourse', 'gibbonCourse.gibbonCourseID = gibbonCourseClass.gibbonCourseClassID')
          ->where('gibbonPlannerEntry.homeworkSubmissionDateOpen <= CURRENT_DATE()')
          ->where("gibbonPlannerEntry.gibbonCrowdAssess = 'Y'")
          ->where('ADDTIME(gibbonPlannerEntry.date,INTERVAL 35 DAY) >= CURRENT_TIMESTAMP()')
          ->where("gibbonPlannerEntry.homeworkCrowdAssessOtherParentsRead = 'Y'");
        break;
    }
     
     return $query;
  }

  public function queryCrowdAssessment(QueryCriteria $criteria,$queryType)
  {

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
      }.
      'familyCurrentStudents' => function($query, $familyID) {
        //Return results when the results from the gateway include gibbonPersonIDs within a particular family ID
        return $query
          ->where("gibbonCourseClassPerson.gibbonPersonID IN (
              SELECT
                gibbonPerson.gibbonPersonID
              FROM gibbonFamilyChild
              INNER JOIN gibbonPerson ON gibbonFamilyChild.gibbonPersonID = gibbonPerson.gibbonPersonID
              INNER JOIN gibbonStudentEnrolment ON gibbonPerson.gibbonPersonID = gibbonStudentEnrolment.gibbonPersonID
              INNER JOIN gibbonYearGroup ON gibbonStudentEnrolment.gibbonYearGroupID = gibbonYearGroup.gibbonYearGroupID
              INNER JOIN gibbonRollGroup ON gibbonYearGroup.gibbonRollGroupID = gibbonRollGroup.gibbonRollGroupID
              WHERE gibbonFamilyChild.gibbonFamilyID = :gibbonFamilyID
              AND gibbonPerson.status = 'Full'
              AND (gibbonPerson.dateStart IS NULL OR gibbonPerson.dateStart <= CURRENT_TIMESTAMP())
              AND (gibbonPerson.dateEnd IS NULL OR gibbonPerson.dateEnd <= CURRENT_TIMESTAMP())")
          ->bindValue('gibbonFamilyID',$familyID);
      }
    ]);
    switch($queryType)
    {
      case "staff":
        return getCrowdAssessmentStaff($criteria);
        break;
      case "student":
        return getCrowdAssessmentStudent($criteria);
        break;
      default:
        return $this->runQuery(getGenericQuery($criteria),$criteria);
        break;
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
