let host = "localhost";
let port = "42069";
let resurce = "oilServer.php";
let url = "ws://" + host + ":" + port; + "/" + resurce;
//! wss einrichten 
let socket = null;

init(); 

function init() {
log("Start init");

  try {
    socket = new WebSocket(url);

    socket.onopen = function() {
      log("Verbunden mit Server");
    };

    socket.onmessage = function(msg) {
      console.debug(msg);
      log("from server: ". msg.data);
    };

    socket.onclose = function(msg) {
      log("Verbindung zum Server getrennt");
      // setTimeout(setSocket,1000);
    };
  
  } catch(ex) {
    alert("Exception: " + ex);
  }
}

function close() { 
  if (socket != null) {
    socket.close(); 
    socket = null; 
    log("Verbindung Geschlossen");
  }
}

function send() {
  let msg = document.getElementById("text").value;
  document.getElementById("text").value = "";
  if (!msg) { console.debug("keine msg angegeben"); return; }
  console.debug("MSG to Server: " + msg);
  socket.send(msg);
}

function log(txt, option = "<br>") {
  document.getElementById("log").innerHTML += option + txt;
}
