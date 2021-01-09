//Anzeigen des LobbyCodes
d3.select("#code").text(localStorage.getItem("code") || "----");

// Socket verbinden
Xhr.rootPath = "..";
let socket = new Socket(localStorage.getItem("ip"), localStorage.getItem("port"));
socket.onopen = (e) => console.debug("WebSocket geöffnet", e);
socket.onmessage = (e) => console.debug("WebSocket Nachricht: ", e.data);

//Frabe aendern durch ein Lable
function colorchange(element){
    console.log(element);
    d3.select(element.parentNode).select('label').style('background-color',element.value);
};