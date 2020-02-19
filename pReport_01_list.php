<?php
    try
    {        
        include('include/db_Conn.php');

        $strSql = "SELECT * ";
        $strSql .= "FROM `tpdt_trn_pd_sch` WHERE `Order` like '" . $pd_ord . "%' ";
        $strSql .= "ORDER BY `Basic start date`, `Basic start date` ";
        //echo $strSql . "<br>";

        $statement = $conn->prepare( $strSql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));  
        $statement->execute();  
        $nRecCount = $statement->rowCount();
        if ($nRecCount >0)
        {
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-hover' id='myTable'>";        
            echo "<thead >";
            echo "<tr class='tr-bk'>";
            echo "<th>Order</th>";
            echo "<th>Material Code</th>";
            echo "<th>Material Name</th>";
            echo "<th>Batch</th>";
            echo "<th>RX No.</th>";
            echo "<th>B.St.Date</th>";
            echo "<th>B.St.T.</th>";
            echo "<th>B.Fi.Date</th>";
            echo "<th>B.Fi.T.</th>";
            echo "<th>Act.St.Date</th>";
            echo "<th>Act.St.T.</th>";
            echo "<th>Act.Fi.Date</th>";
            echo "<th>Act.Fi.T.</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            while ($ds = $statement->fetch(PDO::FETCH_NAMED))
            {
                echo "<tr>";
                echo "<td>" . $ds['Order'] . "</td>";
                echo "<td>" . $ds['Material Number'] . "</td>";
                echo "<td>" . $ds['Material description'] . "</td>";
                echo "<td>" . $ds['Batch'] . "</td>";
                echo "<td>" . $ds['RX No'] . "</td>";
                echo "<td>" . date('d/m/Y', strtotime($ds['Basic start date'])) . "</td>";
                echo "<td>" . date('H:i', strtotime($ds['Basic start time'])) . "</td>";
                echo "<td>" . date('d/m/Y', strtotime($ds['Basic finish date'])) . "</td>";
                echo "<td>" . date('H:i', strtotime($ds['Basic finish time'])) . "</td>";
                echo "<td>" . date('d/m/Y', strtotime($ds['Actual start date'])) . "</td>";
                echo "<td>" . date('H:i', strtotime($ds['Actual start time'])) . "</td>";
                echo "<td>" . date('d/m/Y', strtotime($ds['Actual finish date'])) . "</td>";
                echo "<td>" . date('H:i', strtotime($ds['Actual finish time'])) . "</td>";
                echo "</tr>"; 
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        }
        else
        {
            echo "<script> alert('Warning! No Data ! ... ); window.location.href='pMain.php'; </script>";
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
?>    