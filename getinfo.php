<?php
header("Access-Control-Allow-Origin: 'https://mediaology.com'");
session_start();
require_once "config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

if(isset($_GET['type']))
{
	if($_GET['type']=="getchats")
	{
		$sqlgrab = "SELECT * FROM chats
					WHERE users LIKE '%-".$_SESSION['id']."-%'";
		$result = mysqli_query($link, $sqlgrab);
		$foundpublic = false;
		while($row = mysqli_fetch_array($result)) {
    		echo "-".$row['name'];
			if($row['name']=="public")
			{
				$foundpublic=true;
			}
		}
		if(!$foundpublic)
		{
			echo "-"."public"; 
			$sql = "UPDATE chats
						SET users = concat(users, '-".$_SESSION['id']."-')
						WHERE name = 'public';";
			mysqli_query($link, $sql);
		}
	}
	if($_GET['type']=="createpm")
	{
		if(isset($_GET['user']))
		{
			$getid = "SELECT id FROM users
					WHERE username = '".$_GET['user']."'";
			$result = mysqli_query($link, $getid);
			$ids = mysqli_fetch_array($result);
			$id = $ids["id"];
			if(!isset($id))
			{
				echo "-1";
				exit();
			}
			$createchat = "INSERT INTO chats (name, type, users)
							VALUES (?, ?, ?)";
        
        	if($stmt = mysqli_prepare($link, $createchat)){
				
            	mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_type, $param_users);
				
				if($id<$_SESSION["id"])
				{
					$param_name = $_GET['user'].";".$_SESSION["username"];
				}else{
					$param_name = $_SESSION["username"].";".$_GET['user'];
				}
 	           	$param_type = "private";
				
				$param_users = "-".$id."--".$_SESSION["id"]."-";
				
				$check=mysqli_query($link, "SELECT count(*) AS total FROM chats WHERE name = '".$param_name."'");
				$data=mysqli_fetch_assoc($check);
				if($data['total'] != 0)
				{
					exit();
				}
    	        if(mysqli_stmt_execute($stmt)){
					
        	        mysqli_stmt_store_result($stmt);
            	}
            	mysqli_stmt_close($stmt);
			}
		}
	}
	if($_GET['type']=="getmessages")
	{
		if(isset($_GET['chat'])&&isset($_GET['amount']))
		{
			$check=mysqli_query($link, "SELECT count(*) AS total FROM chats WHERE (name ='".$_GET['chat']."' AND users LIKE '%-".$_SESSION['id']."-%')");
			$data=mysqli_fetch_assoc($check);
			if($data['total'] == 0)
			{
				echo "you do not have access to this channel";
				exit();
			}
			$sqlgrab = "SELECT * FROM
						(
							SELECT * 
							FROM messages
							WHERE board = '" . $_GET['chat'] . "'
							ORDER BY date DESC
							LIMIT ".$_GET['amount'].
						") AS T1
						ORDER BY date ASC";
			$result = $link->query($sqlgrab);

			if ($result->num_rows > 0) {
  				while($row = $result->fetch_assoc()) {
$script="
var xhr = new XMLHttpRequest();
xhr.open('GET', 'https://mediaology.com/mediachat/getinfo.php?type=createpm&user=". $row["user"]."');
xhr.send();
";
    				echo "<div id='line'><button id='name' onclick=\"" . $script . "\">" . $row["user"] . "</button>: <span id='message'>" . $row["message"] . "</span></div>";
  				}
				
			} else {
 				 echo "<div id='line'>no messages yet<div>";
			}
echo
<<<<<<< HEAD
"<style>@font-face{font-family:apple;src:url('GnuUnifontFull-Pm9P.ttf')}#line:hover{background-color:#211e20}#line{font-size:120%;font-family:apple;color:#a0a08b;overflow-wrap:break-word}#name:hover{text-decoration:underline;cursor:pointer}#name{all: unset;color:#e9efec;text-decoration:none;}body{background-color:#555568}</style>";
=======
"<style>@font-face{font-family:apple;src:url('https://mediaology.com/mediachat/GnuUnifontFull-Pm9P.ttf')}#line:hover{background-color:#211e20}#line{font-size:120%;font-family:apple;color:#a0a08b;overflow-wrap:break-word}#name:hover{text-decoration:underline;cursor:pointer}#name{all: unset;color:#e9efec;text-decoration:none;}body{background-color:#555568}</style>";
>>>>>>> 5ad46672324f984d8d2b49c7d0a2610982f605e7
		}
	}
	if($_GET['type']=="leavechat")
	{
		if(isset($_GET['chat']))
		{
			$sqlgrab = "SELECT * FROM chats
						WHERE name = '".$_GET['chat']."'";
			$result = mysqli_query($link, $sqlgrab);
			while($row = mysqli_fetch_array($result)) {
				if($row['type']=="private"&&strpos($row['users'], "-".$_SESSION["id"]."-") !== false)
				{
					$sql = "DELETE FROM chats WHERE name = '".$_GET['chat']."'";
					$result = mysqli_query($link, $sql);
					echo "done";
				}
				else if($row['type']=="group")
				{
					$sql = "UPDATE chats
							SET users = REPLACE(users, '-".$_SESSION["id"]."-', '')
							WHERE name = '".$_GET['chat']."'";
					$result = mysqli_query($link, $sql);
					echo "perfect";
				}
			}
		}
	}
	if(isset($_GET['type']))
    {
	    if($_GET['type']=="getnew")
	    {
	        $sqlgrab = "SELECT * FROM messages WHERE date > (SELECT laston FROM users WHERE id='".$_SESSION["id"]."') AND board IN (SELECT name FROM chats WHERE users LIKE '%-".$_SESSION["id"]."-%') AND user != '".$_SESSION["username"]."'";
			$result = mysqli_query($link, $sqlgrab);
			if($row = mysqli_fetch_array($result)){
			    echo $row['board'] . "<br>" . $row['user'] . ": " . $row['message'];
			}
			$sqlaccount = "UPDATE users
	                    SET laston = '".time()."'
						WHERE username = '".$_SESSION['username']."'";
		    mysqli_query($link, $sqlaccount);
	    }
    }
}

?>