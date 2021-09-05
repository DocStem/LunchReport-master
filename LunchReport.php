<?php
require_once 'ProgramFunctions/SchoolPeriodsSelectInput.fnc.php';


// Set start date.
$start_date = RequestedDate( 'start', date( 'Y-m' ) . '-01' );

// Set end date.
$date = RequestedDate( 'end', DBDate() );
// Add Course Period title header.
		DrawHeader( ProgramTitle() );
		// Set date.
echo '<div id="GFG" >' ;
echo '<style>
.styled-table {
    border-collapse: collapse;
    margin: 25px 0;
    font-size: 0.9em;
    font-family: sans-serif;
    min-width: 400px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
}


.styled-table thead tr {
    background-color: darkred;
    color: #ffffff;
    text-align: left;
}

.styled-table th,
.styled-table td {
    padding: 12px 15px;
}

.styled-table tbody tr {
    border-bottom: 1px solid #dddddd;
}

.styled-table tbody tr:nth-of-type(even) {
    background-color: #f3f3f3;
}

.styled-table tbody tr:last-of-type {
    border-bottom: 2px solid #009879;
}

.styled-table tbody tr.active-row {
    font-weight: bold;
    color: #009879;
}


.styled {
    border: 0;
    line-height: 2.5;
    padding: 0 20px;
    font-size: 1rem;
    text-align: center;
    color: #fff;
    text-shadow: 1px 1px 1px #000;
    border-radius: 10px;
    background-color: rgba(220, 0, 0, 1);
    background-image: linear-gradient(to top left,
                                      rgba(0, 0, 0, .2),
                                      rgba(0, 0, 0, .2) 30%,
                                      rgba(0, 0, 0, 0));
    box-shadow: inset 2px 2px 3px rgba(255, 255, 255, .6),
                inset -2px -2px 3px rgba(0, 0, 0, .6);
}

.styled:hover {
    background-color: rgba(255, 0, 0, 1);
}

.styled:active {
    box-shadow: inset -2px -2px 3px rgba(255, 255, 255, .6),
                inset 2px 2px 3px rgba(0, 0, 0, .6);
}
</style>';

echo "
<!-- Script to print the content of a div -->
    <script> 
        function printDiv() { 
            var divContents = document.getElementById('GFG').innerHTML; 
            var a = window.open('', '', 'height=500, width=500'); 
             
            a.document.write(divContents); 
           
            a.document.close(); 
            a.print(); 
        } 
    </script> ";


echo '<form action="' .
		PreparePHP_SELF( $_REQUEST, array( 'codes', 'month', 'day', 'year' ) ) .
		'" method="POST" >';

		
		

echo	'<select name = "month"><option>Month</option>';
    
    		for($month = 1; $month <= 12; $month++){
    		echo"<option value = '".$month."'>".$month."</option>";
    		}
 
 echo   '</select>';

echo	'     <select name = "day"><option>Day</option>';
         
    	        for($day = 1; $day <= 31; $day++){
    		       echo "<option value = '".$day."'>".$day."</option>";
    		}
    	
echo '</select>';

echo '<select name = "year"><option>Year</option>';
    
    		$y = date("Y", strtotime("+8 HOURS"));
    		for($year = 2020; $y >= $year; $y--){
    			echo "<option value = '".$y."'>".$y."</option>";
    		}
    	
 echo '</select>';
echo '<br /><div class="center">' .
					Buttons( _( 'Run Report' ) ) . '</div>';



echo '</form>';


if(isset($_REQUEST['day']) && is_numeric($_REQUEST['day']) &&
   isset($_REQUEST['month']) && is_numeric($_REQUEST['month']) &&
   isset($_REQUEST['year']) && is_numeric($_REQUEST['year'])
  ) 
{ 

	echo '<h2>Lunch orders for ' . $_REQUEST['month'] . '-' . $_REQUEST['day'] . '-' . $_REQUEST['year'] .'</h2>';

		$lunchDate = $_REQUEST['year'] .'-'. $_REQUEST['month'] .'-' . $_REQUEST['day'] ;
		$SQL_Lunches = "Select first_name, last_name, class, menu_item,lunch_comment, school_date 
		from student_lunches 
		Where menu_item <> 'No Lunch' 
		AND school_date = '" . $lunchDate ."'
		AND syear = '" . UserSyear() ."'
		Order by school_date, class ;";

		$lunchOrders = DBGet( $SQL_Lunches);



		if(count($lunchOrders) > 0){ //This is a school day with Lunch Orders
		//echo '<button target="_blank" onClick="window.print()">Print</button>';
 echo '<input type="button" value="Print" onclick="printDiv()">';


				

			echo '<h2>SUMMARY</h2>';

		//Trick for populating a Pivot Like Table without regard for database Type

		//What is the Row? We have columns already
		$SQL_Rows = "Select class from student_lunches Group By class  Order by class ;";
				$gridRows = DBGet( $SQL_Rows);

		$SQL_Columns = "Select menu_item from student_lunches 
					Where menu_item <> 'No Lunch' 
					AND school_date = '" . $lunchDate . "' 
					AND syear = '" . UserSyear() ."' 
					Group By menu_item  Order by menu_item ;";
				$gridColumns = DBGet( $SQL_Columns);

		$columnTotals = array();

				

		$rowHeaderColumn1 = "Class";

					echo '<table class="styled-table"">
							<thead>
				                <tr>
				                <th>'. $rowHeaderColumn1 . '</th>';

				                foreach($gridColumns as $gridColumn){
				                	foreach($gridColumn as $key => $value){
				                		echo '<th>' . $gridColumn[$key]   .  '</th>';
				                	}
				                }							

				    echo '<th>TOTAL</th>';
				                
				    echo '</tr></thead></thead><tbody>';



				     foreach($gridRows as $gridRow){
				        foreach($gridRow as $key => $value){
				        	echo '<tr>';
				        	echo '<td>' . $gridRow[$key]   .  '</td>';

				            $Rtotal = 0;
				            $colCount = 0;
				            foreach($gridColumns as $gridColumn){
				            	foreach($gridColumn as $colKey => $value){
				                		$SQL_GridData = "Select count(menu_item) 
				                		from student_lunches 
				                		Where class = '" . $gridRow[$key] . "' 
				                		AND menu_item = '" . $gridColumn[$colKey] . "' 
				                		AND syear = '" . UserSyear() ."'
				                		AND school_date = '" . $lunchDate . "';";

				                		$gridData = DBGet( $SQL_GridData);

				                		$Rtotal += $gridData[1]['COUNT'];
				                		$columnTotals[$colCount] += $gridData[1]['COUNT'];
				                		$colCount += 1;
				                		echo '<td>' . $gridData[1]['COUNT'] . '</td>';
				                }
				            }

				            echo '<td><b>' . $Rtotal . '</b></td>';
				            echo '</tr>';
				        }
				    }

				    echo '<tr>';
				    echo '<td>Total</td>';
				    for($a = 0;$a < $colCount;$a++){
				    	echo '<td><b>' . $columnTotals[$a] . '</b></td>';
				    }
				    echo '<tr>';
			echo '</tbody></table>';


			//Produces the Detailed Report
				echo '<table class="styled-table"">
				<thead>
				                <tr>
				                <th>Class </th>
				                <th>First Name</th>
				                <th>Last Name </th>
				                <th>Menu Item </th>
				                <th>Comment </th>
				                </tr>
				                </thead>
				 </thead><tbody>';

				    foreach ((array) $lunchOrders as $lunchOrder){
				        echo'<tr>'; 
				        echo'<td>'. $lunchOrder['CLASS'].'</td>';
				        echo'<td>'. $lunchOrder['FIRST_NAME']."</td>";
				        echo'<td>'. $lunchOrder['LAST_NAME'].'</td>';
				        echo'<td>'. $lunchOrder['MENU_ITEM'].'</td>';
				        echo'<td>'. $lunchOrder['LUNCH_COMMENT'].'</td>';
				        echo'<tr>';
				    }
				echo '</tbody></table>';




	}else{ //No Lunch Orders today
		echo ' There are no Lunch Orders';
	}
echo '</div>';
}