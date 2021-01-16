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
   * @version 16.01.2021
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
      messgae = [false, false];
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
    switch (instruction.cmd) {
      case "socketOpen": this.socketOpen(); break;
      default: console.error("Unknown instruction command", instruction.cmd);
    }
  }

  static socketOpen() {
    console.debug("socket opened");
    const delay = 5000;
    Utils.delay(delay);
    gc.sendInstruction("autotest", delay);
  }

}