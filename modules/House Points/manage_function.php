<?php
class man {
    
    function __construct($guid, $connection2) {
        $this->dbh = $connection2;
        $this->guid = $guid;
        
        $this->yearID = $_SESSION[$guid]['gibbonSchoolYearID'];
        
        $this->studentList = readStudentList($this->dbh, $this->yearID);
        $this->houseList = readHouseList($this->dbh);
        //$this->categoryList = readCategoryList($this->dbh);
        
    }

    function mainForm() {
        echo "<p>&nbsp;</p>";
        echo "<h3>Manage house points for a student</h3>";
        echo "<table class='fullWidth'>";
            echo "<tr>";
                echo "<th style='width: 20%'>Select student</div>";
                echo "<td>";
                    selectStudent($this->studentList);
                echo "</td>";
            echo "</tr>";
        echo "</table>";
        
        echo "<div>&nbsp;</div>";
        
        echo "<div id='studentPoints'></div>";
        
        echo "<h3>Manage house points for house</h3>";
        echo "<table class='fullWidth'>";
            echo "<tr>";
                echo "<th style='width: 20%'>Select House</div>";
                echo "<td>";
                    selectHouse($this->houseList);
                echo "</td>";
            echo "</tr>";
        echo "</table>";
        
        echo "<div>&nbsp;</div>";
        
        echo "<div id='housePoints'></div>";
        
        ?>
        <script>
            $('#studentID').change(function() {
                managePointStudent($('#studentID').val());
            });
            $('#houseID').change(function() {
                managePointHouse($('#houseID').val());
            });
        </script>
        <?php
    }
    
}