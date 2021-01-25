/**
 * Verwaltet Anweisung von und zum Server.
 * @author Tobias
 * @version 16.01.2021
 * @since 16.01.2021
 */
class InstructionHandler {

  // ------------------------------ UTIL -----------------------------------------

  /**
   * Prueft eine Anweisung, ob diese gueltig ist.
   * @author Tobias
   * @version 09.01.2021
   * @since 09.01.2021
   * @param {String} instruction Anweisung.
   * @returns {Boolean} Gibt true zurueck, wenn die Anweisung gueltig ist.
   */
  static isValidInstruction(instruction) {
    if (!Array.isArray(instruction))                                              return false;
    if (instruction.length !== 2)                                                 return false;
    if (typeof instruction[0] !== "number" && typeof instruction[0] !== "string") return false;
    if (typeof instruction[1] !== "number" && typeof instruction[1] !== "string") return false;
    return true;
  }

  /**
   * Nimmt Anweisungen (des Servers) an und extrahiert den Typ und den Wert der Anweisung.
   * @author Tobias
   * @version 19.01.2021
   * @since 16.01.2021
   * @param {String} message Anweisung des Servers.
   * @returns {Object} cmd : Befehl der Anweisung, value : Wert der Anweisung.
   */
  static extractInstruction(message) {
    try {
      message = JSON.parse(message);
      if (!this.isValidInstruction(message)) {
        throw "erol";
      }
    } catch (e) {
      console.error(e);
      message = [false, false];
    }

    return { cmd: message[0], value: message[1] };
  }

  /**
   * Erstellt eine Anweisung aus Parametern,
   * @author Tobias
   * @version 16.01.2021
   * @since 16.01.2021
   * @param {String} cmd Befehl der Anweisung.
   * @param {String} value Wert der Anweisung.
   * @returns {String} Anweisung.
   */
  static makeInstruction(cmd, value) {
    return JSON.stringify([cmd, value]);
  }

  // ------------------------------ HANDLE -----------------------------------------

  /**
   * 
   * @author Tobias
   * @version 16.01.2021
   * @since 16.01.2021
   * @param {Object} instruction 
   * @param {String} instruction.cmd 
   * @param {String} instruction.value 
   * @returns {void} 
   */
  static handle(instruction) {
    let value;
    try { value = JSON.parse(instruction.value); } catch {}

    switch (instruction.cmd) {
      case "socketOpen": this.socketOpen(); break;
      case "userChange": this.userChange(value); break;
      case "userAdd": this.userAdd(value); break;
      case "showCard": this.showCard(value); break;
      case "updateID": this.updateID(value); break;
      default: console.error("Unknown instruction command", instruction.cmd);
    }
  }

  static socketOpen() {
    console.debug("socket opened");
    gc.sendInstruction("getPlayerlist");
    d3.select("#lobby").classed("none", false);
    d3.select("#loading").classed("none", true);
  }

  static userChange(value) {
    for (let k in value) {
      switch (k) {
        case "id": break;
        case "name":  this.setUser(value.id, value.name); break;
        case "color": this.setUser(value.id, null, value.color); break;
        case "ready": this.setUser(value.id, null, null, value.ready); break;
      }
    }
  }

  static userAdd(value) {
    this.setUser(value.id, value.name, value.color, value.ready);
  }

  static setUser(id, name, color, ready) {
    if (id == localStorage.getItem("ID")) return;

    let playerRow = d3.select("#player-" + id);
  
    if (playerRow.node() === null) {
      let row = d3.select("#playerlist")
        .append("div")
          .attr("id", "player-" + id)
          .classed("row", true);

      row.append("div")
        .classed("col", true);

      row.append("div")
        .classed("col", true);
    }

    playerRow = d3.select("#player-" + id);
    if (name  !== null) playerRow.select(".col:nth-child(1)").text(name || "Player #" + id);
    if (color !== null) playerRow.select(".col:nth-child(1)").style("background-color", color || "#a0a0a0").style("color", Utils.fontColorAutoKontrastHex(color || "#a0a0a0"));
    if (ready !== null) playerRow.select(".col:nth-child(2)").text(ready ? "Bereit" : "");
  }

  static updateID(value) {
    localStorage.setItem("ID", value);
  }

  /**
   * 
   * @param {string} Inhalt der Karte.
   */
  static showCard(value) {
    console.info("Karte vom Typ '" + value.type + "' erhalten: " + value.title + "\n" + value.text);
  }

}