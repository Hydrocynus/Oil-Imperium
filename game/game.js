Xhr.rootPath = "..";
// Socket verbinden
gc = new GameControl();

//Anzeigen des LobbyCodes
d3.select("#code").text(localStorage.getItem("code") || "----");

//Frabe aendern durch ein Lable
function colorChange(element){
  d3.select(element.parentNode).select('label').style('background-color',element.value);
  let value = { id: localStorage.getItem('ID'), color: element.value };
  value     = JSON.stringify(value);
  gc.sendInstruction("userChange", value);
};

function nameChange(element) {
  let value = { id: localStorage.getItem('ID'), name: element.value };
  value     = JSON.stringify(value);
  gc.sendInstruction("userChange", value);
}

function readyChange(element) {
  let value = { id: localStorage.getItem('ID'), ready: element.checked };
  value     = JSON.stringify(value);
  gc.sendInstruction("userChange", value);
}