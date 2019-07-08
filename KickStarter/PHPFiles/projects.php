<?php

session_start();

    //set the connection
$db_link = mysqli_connect("localhost", "root", "", "faceAfekaUsers");
if (mysqli_connect_error()) {
    die("ERROR IN DB!!!");
}


$sql = 'SELECT * FROM `projects`';
$query = mysqli_query($db_link, $sql);

if (!$query) {
    die('SQL Error: ' . mysqli_error($db_link));
}


        // $projectsTable = '<table id = "projectsTable">';
        $projectsTable = '<div id = "projectsTable">';


//		echo ;
        
        while ($row = mysqli_fetch_array($query)) {
            //        $amount  = $row['amount'] == 0 ? '' : number_format($row['amount']);

            $img = $row['image'];
            $proj_name = $row['name'];
            $description = $row['description'];

            // $projectsTable .= '<tr>
			// 		<td class = "projectsCell"> <img class = "projectsImgs" src = "'.$img.'"> </td>
            //         <td class = "projectDescription"> <p class = "projDes">'.$description.'</p>'.$description.'</td>';

            $projectsTable .= '<div class = "projectsRow">
					<div class = "projectsCell"> <img class = "projectsImgs" src = "'.$img.'"> </div>
                    <div class = "projectDescription"> <p class = "projDes">'.$description.'</p></div>';

            if ((isset($_SESSION["logged"])) and ($_SESSION["logged"] == "true")) {
                // $projectsTable .= '
				// 				<td class = "projectsCell">
				// 					<input type = "text" name = "donation" placeholder = "place your donation">
				// 					<br/>
				// 					<button class = "button" value = "donate">donate</button>
                // 				</td>';
                
                 $projectsTable .= '
								<div class = "projectsCell">
									<input type = "text" name = "donation" placeholder = "place your donation">
									<br/>
									<button class = "button" value = "donate">donate</button>
								</div>';
            }

            $projectsTable .= '</div><br/>';
            
        }
            // $projectsTable .= '</tr> </table>';
            $projectsTable .= '</div> ';
            echo json_encode($projectsTable);
        
        ?>
		