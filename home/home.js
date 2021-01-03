/**
 * //Test Kommi Tim  // 
 * Ueberprueft den Spiel-Code waehrend der Eingabe auf syntaktische Richtigkeit.
 * Wenn noetig wird der Code sofort angepasst.
 * @author Tobias
 * @version 28.11.2020
 * @since 28.11.2020
 * @param {Element} input Das Inputfeld des Codes.
 * @returns {void}
 */
function checkCode(input) {
  input.value = input.value.toUpperCase();
  input.value = input.value.replace(/\s/, "");
  input.value = input.value.substring(0, input.getAttribute("maxlength"));

  // Button ist erst anklickbar, wenn Code vierstellig ist
  d3.select("button[value=join]").attr("disabled", input.value.length == input.getAttribute("maxlength") ? null : true);
}