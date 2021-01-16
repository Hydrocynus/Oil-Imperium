/**
 * Verwaltet das Spiel.
 * @author Tobias
 * @version 09.01.2021
 * @since 09.01.2021
 */
class GameControl {

  /**
   * Konfiguriert und Verwaltet eine WebSocket Verbindung fuer Interarktionen
   * @author Tobias
   * @version 09.01.2021
   * @since 09.01.2021
   * @param {Socket} [socket] Socket fuer das Spiel, wenn kein Socket uebergeben wurde, wird ein neues erstellt.
   * @returns {void}
   */
  constructor(socket) {
    this.socket = socket;
    if (this.socket === undefined) this.socket = new Socket(localStorage.getItem("ip"), localStorage.getItem("port"));

    this.socket.onerror   = () => console.debug("Websocket konnte nicht verbunden werden!");
    this.socket.onopen    = e  => console.debug("WebSocket geöffnet", e);
    this.socket.onmessage = e  => this.instructionHandler(e.data);

    this.socket.open();
  }

  /**
   * Nimmt Anweisungen (des Servers) an und verarbeitet diese.
   * @author Tobias
   * @version 09.01.2021
   * @since 09.01.2021
   * @param {String} message Anweisung (des Servers).
   * @returns {void}
   */
  instructionHandler(message) {
    try {
      message = JSON.parse(message);
    } catch (e) {
      console.error("Fehler beim Verarbeiten der Anweisung:", e);
    }

    if (!Array.isArray(message) || message.length !== 2) {
      console.error("Fehlerhafte Anweisung erhalten:", message);
    }

    let type  = message[0];
    let value = message[1];

    console.debug("Neue Anweisung (" + type + "): " + value);
  }

  /**
   * Sende Anweisungen an den Server.
   * @author Tobias
   * @version 09.01.2021
   * @since 09.01.2021
   * @param {String} type Typ der Anweisung.
   * @param {String} value Wert der Anweisung.
   */
  sendInstruction(type, value) {
    let message = JSON.stringify([type,value]);
    this.socket.send(message);
  }

  /**
   * Prueft eine Anweisung, ob diese gueltig ist.
   * @author Tobias
   * @version 09.01.2021
   * @since 09.01.2021
   * @param {String} instruction Anweisung.
   * @returns {Boolean} Gibt true zurueck, wenn die Anweisung gueltig ist.
   */
  isValidInstruction(instruction) {
    if (!Array.isArray(instruction))                                              return false;
    if (instruction.length !== 2)                                                 return false;
    if (typeof instruction[0] !== "number" && typeof instruction[0] !== "string") return false;
    if (typeof instruction[1] !== "number" && typeof instruction[1] !== "string") return false;
    return true;
  }

}