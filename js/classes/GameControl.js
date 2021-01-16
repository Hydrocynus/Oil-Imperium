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
    this.socket.onopen    = () => InstructionHandler.handle({ cmd: 'socketOpen', value: '' });
    this.socket.onmessage = e  => InstructionHandler.handle(InstructionHandler.extractInstruction(e.data));

    this.socket.open();
  }

  sendInstruction(cmd, value) {
    const instruction = InstructionHandler.makeInstruction(cmd, value);
    this.socket.send(instruction);
  }

}