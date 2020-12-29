d3.select("#code").text(localStorage.getItem("code") || "----");

function colorchange(element){
    console.log(element);
    d3.select(element.parentNode).select('label').style('background-color',element.value);
}
