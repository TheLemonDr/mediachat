<?php
header('Access-Control-Allow-Origin: *');
session_start();
require_once "config.php";

if (isset($_POST['submit'])){
	$filter= false; 
	$banned = "SELECT word, public FROM banned";
	$result = $link->query($banned);
	if ($result->num_rows > 0) {
 		while($row = $result->fetch_assoc()) {
			foreach(explode(" ",$_REQUEST['msg']) as $word)
			{
				similar_text($word, $row['word'], $perc);
				if($perc > 90)
				{
					if(htmlspecialchars($_REQUEST['board'])=="public")
					{
						if($row['public'] == 1)
						{
							$filter = true;
						}
					}
					if($row['public'] == 0)
					{
						$filter = true;
					}
				}
				
			}
		}
	}
	$sqlsimilar = "SELECT * FROM
					(
						SELECT * 
						FROM messages
						WHERE board = '" . $_REQUEST['board'] . "'
						AND user = '".$_SESSION["username"]."'
						ORDER BY date DESC
						LIMIT 1
					) AS T1
					ORDER BY date ASC";
	$result = $link->query($sqlsimilar);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			similar_text($_REQUEST['msg'], $row['message'], $perc);
			if($perc > 80)
			{
				$filter = true;
			}
		}
	}
	
	if($_REQUEST['msg']!=""&&!$filter)
	{
	$sql = "INSERT INTO messages (user, date, message, board) VALUES (?, ?, ?, ?)";
	if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "siss", $param_user, $param_date, $param_message, $param_board);
            
            // Set parameters
            $param_user = htmlspecialchars($_SESSION["username"]);
			
			$param_date = time();
			$param_message = htmlspecialchars($_REQUEST['msg']);
			$param_board = htmlspecialchars($_REQUEST['board']);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
            } else{
                echo "Oops! Something went wrong. Please try again later.";
				exit();
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
	}
	
}
?>
