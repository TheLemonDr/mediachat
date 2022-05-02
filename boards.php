<?php
// Initialize the session
session_start();
require_once "config.php";

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
if (isset($_POST['submit'])){
	echo "hi";
	if(isset($_REQUEST['chatname']))
	{
		echo "hi";
		$find = "SELECT count(1) AS exist 
		FROM chats WHERE name = '".$_REQUEST['chatname']."'";
		$idstuff ="SELECT * FROM chats 
		WHERE name = '".$_REQUEST['chatname']."'";
		$check=mysqli_query($link, $find);
		$data=mysqli_fetch_assoc($check);
		$get=mysqli_query($link, $idstuff);
		$id=mysqli_fetch_assoc($get);
		if($data["exist"] == 0)
		{
			echo "hi";
			$create = "INSERT INTO chats (name, type, users)
				VALUES (?,?,?)";
			
			if($stmt = mysqli_prepare($link, $create)){
				
            	mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_type, $param_users);
				
				$param_name = $_REQUEST['chatname'];
					
 	           	$param_type = "group";
				
				$param_users = "-".$_SESSION["id"]."-";
				
    	        if(mysqli_stmt_execute($stmt)){
        	        mysqli_stmt_store_result($stmt);
            	}
            	mysqli_stmt_close($stmt);
			}
		}else if($id["type"]!=="private"&&strpos($_REQUEST['chatname'], ";") !== false)
		{
			echo "hi";
			$sql = "UPDATE chats
					SET users = CONCAT(users, '-".$_SESSION["id"]."-')
					WHERE name = '".$_REQUEST['chatname']."'";
			$result = mysqli_query($link, $sql);
			echo $sql;
		}
		
	}
	header("location: boards.php");
    exit;
}
?>
