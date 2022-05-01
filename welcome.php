<?php
header('Access-Control-Allow-Origin: *');
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat</title>
    <style>
        body{ font: 20px sans-serif; text-align: center; }
		a{color:white;text-decoration: none;background-color:black;}
    </style>
	<link rel="stylesheet" href="wel.css">
</head>
<body>
    <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>
	<a href="reset-password.php" class="btn btn-warning">Change Password</a>
    <a href="logout.php" class="btn btn-danger ml-3">Sign Out</a>
    <a href="https://www.patreon.com/bePatron?u=73389187" data-patreon-widget-type="become-patron-button">Become a Patron!</a><script async src="https://c6.patreon.com/becomePatronButton.bundle.js"></script>
	<button id="chatop" onclick="document.getElementById('popup').style.display = 'block'">Chat Options</button>
	<button id="infobut" onclick="document.getElementById('infopanel').style.display = 'block'">Info</button>
	<div id="chat">
	<iframe name="messages" id="messages" src='chat.php' style="display:none;"></iframe>
	<div>
	<iframe name="dummy" id="dummy" src='about:blank'  width="720px" height="490px"></iframe>
	<div id="container" style="position: relative; width: 0; height: 0">
		<canvas id="canvas"></canvas>
	</div>
	</div>
	<form id="chatter" action="chat.php" method="POST" target="messages">
		<select name="board" id="boardselect" onchange="requestText();">
  		</select>
            <input id="msg" name="msg" autocomplete="off" placeholder="Type your message">
            <input type="submit" id="submit" name="submit" value=">">
	</form>
	</div>
	<div id="popup" style="position: relative; width: 0; height: 0; display: none;">
		<div id="settings"><button id="close" onclick="document.getElementById('popup').style.display = 'none'">x</button>
		<ul id="chatstoedit">
			
		</ul>
		<iframe name="boards" src="boards.php" style="display: none;"></iframe>
		<form id="addboards" target="boards" action="boards.php" method="POST">
			<input id="msg" name="chatname" autocomplete="off" placeholder="Name of group chat">
            <input type="submit" id="submit" name="submit" value=">">
		</form>
		</div>
	</div>
	<div id="infopanel" style="position: relative; width: 0; height: 0; display: none;">
		<div id="links">
		    <button id="close" onclick="document.getElementById('infopanel').style.display = 'none'">x</button>
		    <div id="sections">
		        <div id="title">
		             Credits
		        </div>
		        Design and Implentation: FoxMoss<br>
		        Game Of Life Loading Screen: Ivan<br>
		        Login System: tutorialrepublic.com
		    </div>
		    <div id="sections">
		        <div id="title">
		             Links
		        </div>
		        <a href="https://mediaology.com/mediachat">mediaology.com/mediachat</a><br>
		        <a href="https://mediachat.mediaology.com/">mediachat.mediaology.com</a><br>
		        <a href="https://spacyy.com/">spacyy.com</a><br>
		    </div>
		</div>
	</div>
	<script src="chatting.js"></script>
	<script src="life.js"></script>
</body>
</html>