<?php
class StaffAttendanceController
{
    private $sqlCommands = array(
        "GetAttendance" => "
            select 
                sa.gibbonStaffAttendanceID,
                sa.gibbonStaffID,
                sa.gibbonAttendanceCodeID,
                sa.notes,
                sa.startDate,
                sa.endDate,
                ac.name
            from gibbonStaffAttendance sa
            inner join gibbonAttendanceCode ac on ac.gibbonAttendanceCodeID = sa.gibbonAttendanceCodeID;
            where
                sa.startDate >= CURRENT_TIMESTAMP()
        "
    );

    public function GetAllAttendance()
    {
        $db = new DatabaseHelper($pdo);
        try
        {
            return json_encode($db->RunSQL($sqlCommands['GetAttendance'],"StaffAttendance",null));
        }
        catch(PDOException $e)
        {
            throw $e;
        }

    }
}
?>
