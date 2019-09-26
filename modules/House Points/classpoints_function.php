<?php
class cls {
    
    function __construct($guid, $connection2) {
        $this->dbh = $connection2;
        $this->guid = $guid;
        
        $this->yearID = $_SESSION[$guid]['gibbonSchoolYearID'];
        
        $this->classList = $this->readClassList();
        $this->studentList = readStudentList($this->dbh, $this->yearID);
        $this->categoryList = readCategoryList($this->dbh);
        
    }

    function mainForm() {
        echo "<p>&nbsp;</p>";
        echo "<h3>View house points for a class</h3>";
        echo "<table class='fullWidth'>";
            echo "<tr>";
                echo "<th style='width:20%;'>Select class</div>";
                echo "<td style='width:80%;'>";
                    echo "<select id='classID' style='float:left;' class='fullWidth'>";
                        echo "<option value='0'>Please select</option>";
                        while ($row = $this->classList->fetch()) {
                            echo "<option value='".$row['classID']."'>".$row['className']."</option>";
                        }
                    echo "</select>";
                echo "</td>";
            echo "</tr>";
        echo "</table>";
        
        echo "<div>&nbsp;</div>";
        
        echo "<div id='studentPoints'></div>";
        
        ?>
        <script>
            $('#classID').change(function() {
                showClassPoint($('#classID').val());
            });
        </script>
        <?php
    }
    
    function readClassList() {
        $data = array(
            'yearID' => $this->yearID
        );
        $sql = "SELECT gibbonRollGroup.gibbonRollGroupID AS classID,
            gibbonRollGroup.name AS className
            FROM gibbonRollGroup
            WHERE gibbonRollGroup.gibbonSchoolYearID = :yearID
            ORDER BY LENGTH(gibbonRollGroup.name), gibbonRollGroup.name";
        $rs = $this->dbh->prepare($sql);
        $rs->execute($data);
        return $rs;
    }
}