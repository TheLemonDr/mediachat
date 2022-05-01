if(!window.location.href.includes("https:"))
{
	window.location.href =
        window.location.href.replace(
                   'http:', 'https:');
}
function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}
counter = 0;
function reload()
{
    requestNew()
	requestText();
	requestBoards();
	if(counter < 3)
	{
		counter++;
		if(counter==2)
		{
			document.getElementById('container').remove();
		}
	}
	
	sleep(2000).then(() => { reload(); });
}
function leavechat(chat)
{
	var xhr = new XMLHttpRequest();

	xhr.open('GET', 'getinfo.php?type=leavechat&chat=' + chat);
	xhr.onreadystatechange = handler;
	xhr.send();

	function handler() {
  		if (this.readyState === this.DONE) {
    		if (this.status === 200) {
				requestBoards();
   			} else {
			}
    	}
	}
}
lastboards=null;
function requestBoards()
{
	
	var xhr = new XMLHttpRequest();

	xhr.open('GET', 'getinfo.php?type=getchats');
	xhr.onreadystatechange = handler;
	xhr.send();

	function handler() {
  		if (this.readyState === this.DONE) {
    		if (this.status === 200) {
				if(lastboards != this.response)
				{
					boards = this.responseText;
					arrboard = boards.split("-");
					document.getElementById("boardselect").innerHTML="";
					document.getElementById("chatstoedit").innerHTML="";
					for (let i = 1; i < arrboard.length; i++) {
  						document.getElementById("boardselect").innerHTML +=
						"<option value='"+arrboard[i]+"'>"+arrboard[i]+"</option>";
						document.getElementById("chatstoedit").innerHTML +=
						"<li id='chatli'><button id='chatleave' onclick='leavechat(\""+arrboard[i]+"\")'>leave</button>"+arrboard[i]+"</li>";
					}
				}
				lastboards = this.response;
   			} else {
			}
    	}
	}
}
lasttext=null;
function requestText()
{
	var xhr = new XMLHttpRequest();

	xhr.open('GET', 'getinfo.php?type=getmessages&chat='+document.getElementById("boardselect").value+'&amount=50');
	xhr.onreadystatechange = handler;
	xhr.send();

	function handler() {
  		if (this.readyState === this.DONE) {
    		if (this.status === 200) {
				if(lasttext != this.response)
				{
					document.querySelector('#dummy').contentWindow.document.body.innerHTML = this.response;
					document.getElementById("dummy").contentWindow.scrollTo(0, 9999999999999);
					
				}
				lasttext=this.response;
				
   			} else {
			}
    	}
	}
}
function requestNew()
{
	var xhr = new XMLHttpRequest();

	xhr.open('GET', 'getinfo.php?type=getnew');
	xhr.onreadystatechange = handler;
	xhr.send();

	function handler() {
  		if (this.readyState === this.DONE) {
    		if (this.status === 200) {
				if(this.response !== "")
				{
					newtext = this.response;
					title = newtext.split("<br>")[0];
					body = newtext.split("<br>")[1];
					notify(title, body);
				}
				
   			} else {
			}
    	}
	}
}
function notify(title, body) {
  // Let's check if the browser supports notifications
  if (!("Notification" in window)) {
    alert("This browser does not support desktop notification");
  }

  // Let's check whether notification permissions have already been granted
  else if (Notification.permission === "granted") {
    // If it's okay let's create a notification
    var notification = new Notification(title,{
                                 body: body,
                                icon: './../favicon.ico'
                                });
  }

  // Otherwise, we need to ask the user for permission
  else if (Notification.permission !== "denied") {
    Notification.requestPermission().then(function (permission) {
      // If the user accepts, let's create a notification
      if (permission === "granted") {
        var notification = new Notification(title,{
                                 body: body,
                                icon: './../favicon.ico'
                                });
      }
    });
  }

  // At last, if the user has denied notifications, and you
  // want to be respectful there is no need to bother them anymore.
}
function cleartext() {
  sleep(100).then(() => {document.getElementById('msg').value='';requestText();});
}
function cleartextchats() {
  sleep(100).then(() => {document.querySelector('#addboards > #msg').value='';requestBoards();});
}
document.getElementById("chatter").addEventListener("submit", cleartext);
document.getElementById("addboards").addEventListener("submit", cleartextchats);
sleep(2000).then(() => { reload() });