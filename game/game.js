Xhr.rootPath = "..";
// Socket verbinden
gc = new GameControl();

//Anzeigen des LobbyCodes
d3.select("#code").text(localStorage.getItem("code") || "----");

//Frabe aendern durch ein Lable
function colorChange(element){
  d3.select(element.parentNode).select('label').style('background-color',element.value);
  let value = { color: element.value };
  value     = JSON.stringify(value);
  gc.sendInstruction("userChange", value);
};

function nameChange(element) {
  let value = { name: element.value };
  value     = JSON.stringify(value);
  gc.sendInstruction("userChange", value);
}

function readyChange(element) {
  let value = { ready: element.checked };
  value     = JSON.stringify(value);
  gc.sendInstruction("userChange", value);
}