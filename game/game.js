Xhr.rootPath = "..";

//Anzeigen des LobbyCodes
d3.select("#code").text(localStorage.getItem("code") || "----");

// Socket verbinden
let gc = new GameControl();

//Frabe aendern durch ein Lable
function colorchange(element){
    console.log(element);
    d3.select(element.parentNode).select('label').style('background-color',element.value);
};