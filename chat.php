<?php
header('Access-Control-Allow-Origin: *');
session_start();
require_once "config.php";

if (isset($_POST['submit'])){
	if($_REQUEST['msg']!="")
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